#!/usr/bin/env python3
"""Read a phpMyAdmin SQL dump as data; export only canonical Marukka snippets.

Never executes SQL. The output contains article content, not source users/config.
"""
import argparse
import collections
import hashlib
import html
from html.parser import HTMLParser
import json
from pathlib import Path
import re

TABLES = {
    'info', 'sites', 'sections', 'entries', 'elements', 'elements_sites',
    'content', 'fields', 'matrixblocks', 'matrixblocks_owners',
    'matrixblocktypes', 'matrixcontent_inhaltmatrix', 'relations',
}
SECTION_CATEGORIES = {'craftsnippets': 'craft', 'mac': 'mac', 'jssnippets': 'javascript', 'css': 'css'}
LANGUAGES = {'plaintext', 'javascript', 'typescript', 'php', 'twig', 'css', 'xml', 'json', 'bash', 'sql', 'yaml'}


def parse_dump(source):
    """Parse INSERT tuples with MySQL quoting, backslash escapes and NULLs."""
    if re.search(r'^SET\s+(?:SESSION\s+)?SQL_MODE\s*=\s*[\'\"][^;]*NO_BACKSLASH_ESCAPES', source, re.M | re.I):
        raise ValueError('NO_BACKSLASH_ESCAPES dumps are not supported')
    tables = collections.defaultdict(list)
    escapes = {'0': '\0', 'b': '\b', 'n': '\n', 'r': '\r', 't': '\t', 'Z': '\x1a'}
    headers = re.finditer(r'INSERT INTO `([^`]+)`\s*\(([^)]+)\)\s*VALUES\s*', source)
    for match in headers:
        table = match[1]
        if table not in TABLES:
            continue
        columns = re.findall(r'`([^`]+)`', match[2])
        position = match.end()
        while True:
            while position < len(source) and (source[position].isspace() or source[position] == ','):
                position += 1
            if position >= len(source) or source[position] != '(':
                break
            position += 1
            values = []
            while True:
                while source[position].isspace():
                    position += 1
                if source[position] == "'":
                    position += 1
                    value = []
                    while True:
                        char = source[position]
                        position += 1
                        if char == '\\':
                            char = source[position]
                            position += 1
                            # MySQL preserves these pattern escapes outside LIKE.
                            value.append('\\' + char if char in '%_' else escapes.get(char, char))
                        elif char == "'":
                            if source[position] == "'":
                                value.append("'")
                                position += 1
                            else:
                                break
                        else:
                            value.append(char)
                    value = ''.join(value)
                else:
                    start = position
                    while source[position] not in ',)':
                        position += 1
                    token = source[start:position].strip()
                    if token.upper() == 'NULL':
                        value = None
                    elif re.fullmatch(r'-?\d+', token):
                        value = int(token)
                    else:
                        raise ValueError(f'Unsupported SQL value in {table}; refusing to guess')
                values.append(value)
                while source[position].isspace():
                    position += 1
                delimiter = source[position]
                position += 1
                if delimiter == ')':
                    break
                if delimiter != ',':
                    raise ValueError(f'Invalid tuple in {table}')
            if len(values) != len(columns):
                raise ValueError(f'Column/value mismatch in {table}')
            tables[table].append(dict(zip(columns, values)))
    return tables


class PlainText(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.parts = []

    def handle_data(self, data):
        self.parts.append(data)

    def handle_starttag(self, tag, attrs):
        if tag in ('p', 'br', 'li', 'div'):
            self.parts.append('\n')

    def handle_endtag(self, tag):
        if tag in ('p', 'li', 'div'):
            self.parts.append('\n')


def plain_text(value):
    parser = PlainText()
    parser.feed(value or '')
    return re.sub(r'\n{3,}', '\n\n', ''.join(parser.parts)).strip()


def export_snippets(tables):
    index = lambda name: {row['id']: row for row in tables[name]}
    elements, sections, fields = index('elements'), index('sections'), index('fields')
    block_records, block_types = index('matrixblocks'), index('matrixblocktypes')
    primary = next(row for row in tables['sites'] if row['primary'] and not row['dateDeleted'])
    site_id = primary['id']
    contents = {(row['elementId'], row['siteId']): row for row in tables['content']}
    localized = {(row['elementId'], row['siteId']): row for row in tables['elements_sites']}
    matrix = {(row['elementId'], row['siteId']): row for row in tables['matrixcontent_inhaltmatrix']}
    owners = collections.defaultdict(list)
    for row in tables['matrixblocks_owners']:
        owners[row['ownerId']].append(row)
    relations = collections.defaultdict(list)
    for row in tables['relations']:
        relations[row['sourceId']].append(row)
    articles = []
    counts = collections.Counter()
    for entry in tables['entries']:
        eid = entry['id']
        element = elements[eid]
        section = sections[entry['sectionId']]['handle']
        site = localized.get((eid, site_id), {})
        if section not in SECTION_CATEGORIES:
            continue
        if any(element.get(key) for key in ('draftId', 'revisionId', 'dateDeleted', 'archived')):
            continue
        if not element['enabled'] or not site.get('enabled'):
            continue
        if entry['expiryDate']:
            raise ValueError(f'Entry {eid} has an expiry date; explicit review is required')
        content = contents[(eid, site_id)]
        categories = [SECTION_CATEGORIES[section]]
        flags = []
        for relation in relations[eid]:
            if fields[relation['fieldId']]['handle'] != 'kategorie':
                continue
            language_title = contents.get((relation['targetId'], site_id), {}).get('title')
            slug = {'Twig': 'twig', 'CSS': 'css', 'JavaScript': 'javascript'}.get(language_title)
            if slug and slug not in categories:
                categories.append(slug)
            elif language_title and not slug:
                flags.append(f'Original language relation: {language_title}; no new top-level category added.')
        blocks = []
        for owner in sorted(owners[eid], key=lambda row: row['sortOrder']):
            bid = owner['blockId']
            record, block_element = block_records[bid], elements[bid]
            if fields[record['fieldId']]['handle'] != 'inhaltMatrix':
                continue
            if block_element['dateDeleted'] or not block_element['enabled']:
                continue
            block_site = localized.get((bid, site_id))
            if not block_site or not block_site['enabled']:
                continue
            source = matrix.get((bid, site_id))
            if source is None:
                raise ValueError(f'Primary-site Matrix content missing for block {bid}')
            kind = block_types[record['typeId']]['handle']
            common = {'sourceId': bid, 'sourceUid': block_element['uid']}
            if kind == 'code':
                code = source['field_code_code'] or ''
                language = source['field_code_codelang'] or 'plaintext'
                language = {'html': 'xml', 'js': 'javascript', 'shell': 'bash'}.get(language, language)
                if language not in LANGUAGES:
                    flags.append(f'Unknown code language {language} in source block {bid}; imported as plain text.')
                    language = 'plaintext'
                if not code.strip():
                    flags.append(f'Empty source code block {bid} omitted.')
                    continue
                blocks.append({**common, 'type': 'code', 'code': code, 'language': language})
                counts['code'] += 1
                if language == 'twig' and any(token in code for token in ('password_hash(', 'find .', 'aspect-ratio:', 'scrollbar-gutter:', 'overflow-y:', 'background-color:')):
                    flags.append(f'Review stored Twig language label on source block {bid}; code was not altered.')
            elif kind == 'text':
                rich = source['field_text_text'] or ''
                title = source.get('field_text_titel_xcrxjmbh') or ''
                if title:
                    rich = f'<h2>{html.escape(title)}</h2>\n' + rich
                if not rich.strip():
                    flags.append(f'Empty source text block {bid} omitted.')
                    continue
                blocks.append({**common, 'type': 'text', 'html': rich})
                counts['text'] += 1
                if '<table' in rich:
                    flags.append('Large HTML table: review presentation and editor round-trip.')
                if '<pre' in rich:
                    flags.append('Preformatted code inside rich text retained; review formatting.')
            elif kind == 'ueberschrift':
                title = source['field_ueberschrift_titel'] or ''
                level = source['field_ueberschrift_ueberschriftTyp']
                level = level if level in ('h2', 'h3', 'h4') else 'h2'
                if title:
                    blocks.append({**common, 'type': 'text', 'html': f'<{level}>{html.escape(title)}</{level}>'})
                    counts['heading'] += 1
            elif kind == 'bild':
                image_relations = [r for r in relations[bid] if fields[r['fieldId']]['type'].endswith('Assets')]
                if image_relations:
                    raise ValueError(f'Image block {bid} has assets; explicit asset import is required')
                flags.append(f'Image block {bid} has no linked asset in the source; omitted.')
            else:
                raise ValueError(f'Unsupported source block {kind} on entry {eid}')
        if not blocks:
            raise ValueError(f'No article body found for {eid}')
        seo = {'title': content['field_seoTitle'], 'description': content['field_seoDescription']}
        if any(seo.values()):
            flags.append('Original custom SEO metadata retained in the import report for editorial review.')
        if not content.get('field_lead'):
            flags.append('No source lead: add a lead or independent teaser before publishing.')
        articles.append({
            'sourceId': eid, 'sourceUid': element['uid'], 'sourceSection': section,
            'title': content['title'], 'slug': site['slug'], 'legacyPath': '/' + site['uri'],
            'postDate': entry['postDate'], 'dateCreated': element['dateCreated'],
            'lead': plain_text(content.get('field_lead')), 'categories': categories,
            'legacySeo': seo, 'blocks': blocks, 'review': list(dict.fromkeys(flags)),
        })
    slugs = [row['slug'] for row in articles]
    if len(slugs) != len(set(slugs)):
        raise ValueError('Source slug collision; explicit resolution is required')
    return {
        'format': 'marukka-legacy-snippets', 'version': 1,
        'sourceCraftVersion': tables['info'][0]['version'],
        'sourceSite': {'id': site_id, 'language': primary['language']},
        'summary': {'entries': len(articles), 'blocks': dict(counts)},
        'articles': articles,
    }


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('dump', type=Path)
    parser.add_argument('--output', type=Path, required=True)
    args = parser.parse_args()
    raw = args.dump.read_bytes()
    data = export_snippets(parse_dump(raw.decode('utf-8')))
    data['sourceSha256'] = hashlib.sha256(raw).hexdigest()
    args.output.parent.mkdir(parents=True, exist_ok=True)
    args.output.write_text(json.dumps(data, ensure_ascii=False, indent=2) + '\n')
    print(json.dumps(data['summary']))
    print(f'Article-only export written to {args.output}. No SQL executed.')


if __name__ == '__main__':
    main()
