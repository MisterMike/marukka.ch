# Selective legacy content importer

This importer reads an old Marukka phpMyAdmin dump as **data**, exports only the supported primary-site articles, and creates disabled entries using Craft APIs. It never executes source SQL or imports users, credentials, plugins, Project Config, drafts or revisions.

The approved source produced 28 snippets, 43 Code blocks and 46 Text blocks. The latter include 10 converted H2/H3 headings. Empty text and unlinked image blocks are flagged and omitted. Original slugs, source UIDs, publication dates, existing leads and body order are retained. Code strings are unchanged. Existing language labels are kept for review, with the required HTML → XML alias for highlighting.

## Run

Back up the target database first. Run against the intended target environment; a Git push does not transfer this database content.

```sh
ddev exec php craft db/backup --interactive=0
python3 -B scripts/export-legacy-snippets.py storage/temp/old_marukka.sql \
  --output storage/temp/legacy-snippets.json

# Default mode validates the complete import, then rolls back all entries.
ddev exec php scripts/import-legacy-snippets.php storage/temp/legacy-snippets.json

# Explicitly create the disabled entries after inspecting the dry run.
ddev exec php scripts/import-legacy-snippets.php storage/temp/legacy-snippets.json --apply
```

On another configured Craft environment, omit `ddev exec` and securely supply the source export. Keep source dumps and exports outside the public web root and out of version control. Both `storage/temp/` and backups are ignored by Git and excluded from deployment.

## Safety and reruns

- Uses source entry UIDs for identity. Existing entries are skipped, so editorial changes and enablement are never overwritten.
- Rejects target slug collisions, missing categories, unknown block types, missing primary-site content, unsupported SQL values and unexpected expiry dates.
- Writes in a database transaction. Any failed content check rolls back the batch.
- Dry-run mode is the default. `--apply` is required to commit.
- All newly created parent entries have `enabled = false`; no snippets are published automatically.
- Verifies block order and count, exact code, language values, publication dates, retained table rows, rich-text words and preformatted whitespace after saving.
- Does not derive teasers from article bodies, invent reading times, select featured articles or activate redirects.

`legacy-import-dry-run.json` or `legacy-import-result.json` is written beside the article export. Reports include edit links, review flags, original URLs and source custom SEO values. A rerun replaces the latest result report with its skip results, while the article export retains the original plan. The 301 map in `legacy-redirects.json` was prepared after the approved local run; install redirects only as targets are published.

The existing CKEditor field now exposes table, list, underline, inline-code, code-block and source-editing controls, preserving the kinds of content used by the imported articles. Original text-block titles are retained as H2 headings. Generic Code entry titles do not create invented article headings.

## Tests

```sh
python3 -B -m unittest discover -s scripts/tests -p 'test_*.py'
ddev exec php scripts/check-marukka.php
```

The parser tests cover escaped code, punctuation inside SQL strings, NULLs, multiple tuples, Unicode, quoted strings, literal LIKE escapes and plain-text lead conversion. The full source dry run also validates actual target field serialization before import.

The local acceptance check rendered all 28 disabled article pages, compared all 43 rendered code panels against the source, verified 89 persisted blocks and the 242-row table, and confirmed zero public snippets. A second apply run skipped all 28 existing entries. See [LEGACY-SNIPPET-REVIEW.md](LEGACY-SNIPPET-REVIEW.md) for editorial review.
