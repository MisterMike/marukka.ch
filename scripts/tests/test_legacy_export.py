"""Parser edge cases that could otherwise silently corrupt imported code."""
import importlib.util
from pathlib import Path
import unittest

spec = importlib.util.spec_from_file_location('legacy_export', Path(__file__).parents[1] / 'export-legacy-snippets.py')
export = importlib.util.module_from_spec(spec)
spec.loader.exec_module(export)


class DumpParserTests(unittest.TestCase):
    def test_escaped_code_nulls_and_extended_rows(self):
        sql = r"INSERT INTO `content` (`id`, `title`, `field_lead`) VALUES (1, 'a,b); -- not a statement', NULL), (2, 'it\'s quoted', 'line1\n  \"quote\" \\path');"
        rows = export.parse_dump(sql)['content']
        self.assertEqual(rows[0], {'id': 1, 'title': 'a,b); -- not a statement', 'field_lead': None})
        self.assertEqual(rows[1]['title'], "it's quoted")
        self.assertEqual(rows[1]['field_lead'], 'line1\n  "quote" \\path')

    def test_double_quotes_unicode_and_literal_like_escapes(self):
        sql = "INSERT INTO `content` (`id`, `title`, `field_lead`) VALUES (1, 'Grüsse: it''s', 'one\\%two\\_three');"
        row = export.parse_dump(sql)['content'][0]
        self.assertEqual(row['title'], "Grüsse: it's")
        self.assertEqual(row['field_lead'], r'one\%two\_three')

    def test_does_not_execute_or_extract_other_tables(self):
        sql = "DROP TABLE content; INSERT INTO `users` (`id`, `password`) VALUES (1, 'sensitive'); INSERT INTO `content` (`id`) VALUES (7);"
        self.assertEqual(dict(export.parse_dump(sql)), {'content': [{'id': 7}]})

    def test_rejects_ambiguous_sql_values(self):
        with self.assertRaises(ValueError):
            export.parse_dump('INSERT INTO `content` (`id`) VALUES (NOW());')

    def test_lead_is_plain_text(self):
        self.assertEqual(export.plain_text('<p>A &amp; B</p><p>Next<br>line</p>'), 'A & B\n\nNext\nline')


if __name__ == '__main__':
    unittest.main()
