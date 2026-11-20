# Old Marukka content import assessment

Source inspected: local `storage/temp/old_marukka.sql` (approximately 10 MB), containing Craft **4.14.5** data. The source `info` record was updated on 17 November 2026. Target: the current Craft **5.12.1** snippet model.

The dump was parsed as data; it was not executed or restored into the current database. No source user records, credentials, configuration or article bodies are included in this report. After this assessment was approved, the 28 snippets were imported **locally as disabled entries**. See [the review checklist](LEGACY-SNIPPET-REVIEW.md) for the completed import and per-entry review links.

## Recommendation

Proceed with a selective, repeatable content importer using Craft's element APIs. Import the 28 canonical published snippets into initially disabled entries for review. Do not restore the source SQL over the new database, apply its Project Config, or bring across its plugins, users, revisions or site configuration.

The content is a good fit: the relevant articles use only text, code, heading and image blocks. There is no need to reproduce the old site's full Matrix model.

## Inventory

| Source section | Snippets | Default target category |
| --- | ---: | --- |
| `craftsnippets` | 20 | Craft CMS |
| `mac` | 3 | Mac |
| `jssnippets` | 2 | JavaScript |
| `css` | 3 | CSS |
| **Total** | **28** | |

These are enabled canonical entries on the primary German/Swiss site, without expiry dates. Their publication dates span June 2020–May 2025. The source has 275 entry records in total, but many are revisions, drafts or unrelated content; those must not be imported as articles.

The 28 snippets contain these enabled primary-site Matrix blocks:

| Source block | Count | Target treatment |
| --- | ---: | --- |
| `code` | 43 | Code blocks, retaining code and order |
| `text` | 38 | Rich-text blocks; two are empty and can be skipped |
| `ueberschrift` | 10 | Rich-text blocks containing escaped H2/H3 headings |
| `bild` | 2 | No asset relations exist in the dump; skip and flag for review |

Twenty snippets have an existing lead. There are no duplicate primary-site slugs among the 28 candidates. Only one candidate has populated custom SEO title/description fields. Seven snippets have old language/category relations; these supplement, rather than replace, section-based categorization.

## Field mapping

| Source | Target |
| --- | --- |
| Title | `title` |
| Primary-site slug | `slug`, preserving existing spellings |
| Publication date | `postDate` |
| `field_lead` | `lead` |
| Original section | Existing `kategorie` entry |
| Relevant `sprachen` relations | Existing additional categories where appropriate, subject to review |
| `inhaltMatrix` | `contentMatrix`, preserving primary-site order |
| Text block HTML | `text.richText`, sanitized by the target field |
| Text block's optional title | Prepend an escaped heading within that block's rich text |
| Heading block title and level | Text block with H2/H3 markup |
| `field_code_code` | `code.code`, unchanged |
| `field_code_codelang` | `code.language`, with `html` mapped to `xml` |
| Original source entry UID | Can identify imported entries for safe reruns; validate against existing target UIDs first |

The new `teaser`, `teaserIsCode`, `teaserLanguage`, `featured` and `readingMinutes` fields have no equivalent source values. Leave them unset/default rather than extracting code or inventing metadata. The cards already fall back to `lead` for 20 entries; the other eight can receive editorial teasers later. Keep teasers independent of Matrix content.

## Review points

- **Code language labels:** the stored distribution is 13 JavaScript, 22 Twig, 6 PHP, 1 HTML and 1 CSS blocks. Some labels are inaccurate. Examples include CSS in “Kombiniertes Aspect-ratio” and “Scrollbar Jump Fix”, and a shell command in “Dateien aus Unterordnern bewegen” labeled Twig. Preserve code exactly; review highlighting labels separately.
- **Translations:** the source has German and English sites. The three CSS articles have identical content in both locales, so importing both would create duplicates. Two other articles have leftover English-only block records. Import the primary-site article bodies only; those records are not missing German content.
- **Rich-text fidelity:** retain inline code, lists and links. “Add getUrl() to User element” contains an embedded preformatted code panel in rich text. “Mac SMC Sensor keys” contains a large table (242 rows including its header, approximately 69 KB of HTML). Both need a target rendering/editor round-trip check before approval. Some text blocks have their own titles, which must not be dropped.
- **Images:** neither of the two image blocks in candidate articles has an asset relation. The dump contains 28 asset metadata records overall but not the binary files. There are no image URLs embedded in the candidates' rich text. Do not invent replacements; review the two affected articles (“Listing all structure section handles” and “Kombiniertes Aspect-ratio”).
- **Categories:** section-based mapping is straightforward, but a few articles are filed under Craft while discussing JavaScript or PHP. Review secondary categories; do not turn all ten `sprachen` entries into new top-level categories automatically.
- **SEO and URLs:** the new `/snippets/{slug}` URLs differ from old `/craft-snippets/{slug}`, `/mac/{slug}`, `/javascript/{slug}` and `/css/{slug}` URLs. Generate an exact 301 redirect map. Preserve the one populated custom SEO record separately or add explicit target SEO fields; do not silently discard it.
- **Content age:** importing an article does not verify that its old Craft/Commerce/plugin APIs remain current. Keep the original dates and review technical accuracy before publishing.

Exclude the source homepage, navigation/index pages, test/news entries, contact/legal pages and the demonstration Laravel page from the snippet import. There are no standalone Laravel snippet entries in this dump.

## Proposed execution

1. Back up the target database and confirm the 28-entry source manifest.
2. Produce a dry-run report with field mappings, category assignments, legacy URLs and review flags.
3. Run the importer against the local target via Craft's APIs, initially disabling imported snippets. Use source UIDs to make reruns safe.
4. Verify article counts, block order, exact code strings, text titles, dates, slug uniqueness and category relations. Confirm the table and embedded code survive rich-text sanitization and editing.
5. Render representative articles and compare against source records; resolve language/image/SEO flags.
6. Publish only after editorial review and install the legacy URL redirects.

`storage/temp/` is ignored by Git so the source database cannot be accidentally staged with the implementation.
