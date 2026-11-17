# Marukka implementation

## Inspection and decisions

The new blueprint uses Craft CMS **5.12.1**, PHP 8.4 locally, Vite 6, Tailwind CSS 4 and Alpine.js. Initially it contained generic fields and a few Twig routers, but **no sections, entry types or articles** in its database. The previous site's large working-tree replacement was already present; it was not reverted.

The supplied mockups define the design. Native CSS nesting, custom properties and individual component files carry the visual identity. Tailwind handles short layout utility lists. No React, Bootstrap, component library or new Craft plugin was added. CKEditor, Vite and SEOMate were already Composer dependencies; their Craft installations are enabled in Project Config.

The homepage newsletter panel was replaced with an editorial note because there is no subscription backend. Footer links point to real archive/topic pages rather than invented legal or contact pages. No mockup articles were imported or published.

## Significant files

- `templates/_layouts/base.twig`: semantic shell, Vite entry, SEO integration, skip link.
- `templates/_includes/{header,footer}.twig`: responsive Alpine navigation, fullscreen mobile overlay with large links, real search form and footer. The mobile menu locks background scrolling, traps focus, closes on Escape and restores focus to its toggle.
- `templates/_pages/{homepage,archive,category,topics,article,default}.twig`: content queries and page composition.
- `templates/_components/{snippet-card,code-preview,category-card,pagination,hero,empty-state,brand,icon}.twig`: shared presentation components with explicit inputs.
- `templates/_blocks/{text,code,image,default}.twig` and `_routers/component.twig`: Matrix rendering.
- `templates/_errors/{404,500}.twig`: site-consistent error views.
- `src/css/app.css`: sole stylesheet entry; imports Tailwind, base tokens and separate component/section styles.
- `src/css/base/{variables,typography}.css`: tokens, self-hosted fonts, baseline accessibility.
- `src/css/components/*.css`, `src/css/sections/*.css`: native nested CSS for the recurring components and layouts.
- `src/js/app.js`: sole JS entry, registers Alpine components before starting, conditionally starts highlighting.
- `src/js/components/{navigation,codeCopy,highlightCode}.js`: menu, clipboard and lazy-loaded syntax highlighting.
- `web/assets/brand-mark.svg`, `web/assets/template/**/*.webp`: brand mark and optimized derivatives of the provided PNGs. Original assets remain intact.
- `src/fonts/*.woff2` and `OFL-*.txt`: locally served Manrope and IBM Plex Mono with licenses.
- `vite.config.js`, `postcss.config.js`, `package.json`, `package-lock.json`: Tailwind Vite integration, the new entry, highlight.js, dependency lock.
- `.ddev/config.yaml`: `web` document root and Vite proxy ports 3005/3006.
- `config/{general,vite,routes,seomate,marukka}.php`: environment compatibility, asset integration, archive routes, SEO and default decorative topic artwork mapping.
- `config/project/**`: Craft-generated fields, entry types, sections, plugin state, upload filesystems and field layouts.
- `modules/sitemodule/fields/WhitespaceText.php`, `modules/sitemodule/SiteModule.php`: a registered Plain Text subclass that preserves code whitespace.
- `scripts/{setup-marukka,inspect-content,check-marukka}.php`, `scripts/check-frontend.mjs`: blueprint bootstrap, inspection and validation.
- `.gitignore`: build products, CP resources and uploaded content remain out of source control.
- `PRODUCT.md`, `DESIGN.md`, `.impeccable/design.json`: durable project and design references.

## Content model

| Section | Type | Entry type | URL |
| --- | --- | --- | --- |
| `home` | Single | `homepage` | `/` |
| `categories` | Channel | `category` | `/topics/{slug}` |
| `snippets` | Channel | `snippet` | `/snippets/{slug}` |

The archive is `/snippets`, topic index `/topics`, and search `/search?q=...`. Category links perform real relation filtering. Sorting supports newest, oldest and title; standard GET forms work without JavaScript. Archive pages contain 12 snippets. Out-of-range page requests redirect to the first archive page. Related snippets use `kategorie` relations.

The six initial taxonomy entries are Craft CMS (`craft`), Twig (`twig`), CSS (`css`), Laravel (`laravel`), Mac (`mac`) and JavaScript (`javascript`). Their titles and introductions are editable. `categoryImage` overrides the bundled artwork; known slugs otherwise use `config/marukka.php`. Future categories can use uploaded artwork. Taxonomy entries are content and are not transported by Project Config.

### New fields

| Handle | Type | Usage |
| --- | --- | --- |
| `teaser` | Multiline Plain Text subclass | Independent text/code excerpt; no Matrix extraction |
| `teaserIsCode` | Lightswitch | Selects code presentation |
| `teaserLanguage` | Dropdown | Language; conditionally visible when `teaserIsCode` is on |
| `lead` | Multiline Plain Text | Homepage/category intro, article lead, teaser fallback |
| `featured` | Lightswitch | Homepage featured selection; latest entries used when none selected |
| `readingMinutes` | Number | Optional author-supplied reading time; publication date used when absent |

Supported languages: plain text, JavaScript, TypeScript, PHP, Twig, CSS, HTML/XML, JSON, Bash, SQL and YAML.

### Existing fields reused

- `contentMatrix`: Text, Code and Image entry types replace the empty blueprint's references to missing block types.
- `kategorie`: Entries relation points at `categories`.
- `assetSingle`: aliases `heroImage` (snippet), `categoryImage` (category), `image` (image block).
- `plainText`: aliases `heroTitle` (homepage) and `caption` (image block).
- `richText`: CKEditor content for Text blocks, with H2–H4 headings to preserve the page hierarchy.
- `textPlainMultiline`: alias `code` for Code blocks; uses the same whitespace-preserving subclass as `teaser`.
- `teaserLanguage`: alias `language` in Code blocks.

Craft's standard Plain Text normalizer trims code. `WhitespaceText` keeps its ordinary multiline editing UI but overrides normalization and serialization to preserve indentation, blank lines and literal shortcode-like text. It is part of the existing site module, not an extra plugin. Deploy module code together with its Project Config.

Text blocks render CKEditor's sanitized rich text. Code blocks use escaped output, never `raw`. Unknown Matrix block types are intentionally omitted until their template is provided. The unused legacy generic fields from the supplied blueprint remain available; some refer to plugins absent from this blueprint and are not attached to the new layouts.

## Teaser and code behavior

One `snippet-card.twig` handles both modes. A nonempty code teaser uses an escaped `<pre><code>`; a text teaser renders as escaped text. Empty teasers fall back to `lead`, then a neutral invitation to read the note. Matrix content is never inspected for previews. Card lists eager-load category relations and do not query Matrix bodies.

Highlight.js core and only the languages present on the page load asynchronously. Plain code stays readable if JavaScript or a chunk fails. Article code controls copy the code element's `textContent`; failure selects the code and announces keyboard-copy instructions. Teaser previews clip long lines; article blocks support horizontal keyboard scrolling. Teaser code is manually selected by the editor, so keep it short.

## Development and deployment

```sh
# Existing local project
npm ci
npm run dev
# HTTPS HMR is proxied at https://marukka.ch.ddev.site:3006

# Production assets
npm run build

# Apply the committed schema on another configured Craft environment
php craft up --interactive=0
```

Use `ddev exec` before the npm/php commands when using the local container. Craft's manifest stays at `web/dist/.vite/manifest.json`. The build directory is ignored and should be generated in deployment. Set a complete `PRIMARY_SITE_URL` including scheme. Both `CRAFT_ENVIRONMENT` and the supplied blueprint's `ENVIRONMENT` are supported; use `production` on the live site. DDEV now serves `web`, matching Craft and Vite. Existing local `.env` was corrected to `https://marukka.ch.ddev.site` without committing it.

Fresh installations need taxonomy content. Either create the six entries manually in Craft, or run `php scripts/setup-marukka.php --categories` **on the new blueprint only**. That bootstrap is not a migration from the old website. It is intended for this empty starter, not for an independently customized existing site. Applying Project Config is the normal deployment workflow.

Local upload filesystems point to `web/uploads/images` and `web/uploads/documents`; ensure these directories are writable and persisted on your host. Configure remote storage through Craft if required by deployment.

## Validation

- Production Vite build passes; CSS is about 6.7 KB gzip, main JS about 21 KB gzip. Highlight.js and individual grammar chunks load only when needed. Fonts total about 39 KB; the hero is about 132 KB and category WebPs are roughly 4–20 KB each.
- `ddev exec php scripts/check-marukka.php` passes integration checks for paging, escaped teasers, empty fallback, Matrix text/code, exact whitespace, related entries, featured selection, six categories, the CP language condition and compilation of every Twig template. Fixtures are enclosed in a database transaction and rolled back even on failure.
- `node scripts/check-frontend.mjs` passes menu state, exact clipboard content and denied-clipboard fallback checks.
- Vite serves the development entry through the HTTPS proxy; the production manifest fallback works when the dev server is stopped. Browser HMR interaction could not be verified.
- HTTP checks pass for home, archive, topics, category, search, malformed query parameters and the custom 404; out-of-range pagination redirects correctly.
- Metadata checks verify one H1, one canonical and description, image dimensions/alt attributes and a single main JS load.
- Project Config schema check passes and reports no pending YAML changes. The mechanical design scan reports no findings. `git diff --check` passes.
- Browser automation was unavailable (the browser runtime returned no browsers). Desktop/mobile screenshots, computed contrast/layout, keyboard interaction in a real browser and clipboard permissions still require visual QA. Responsive rules and reduced-motion behavior are implemented but are not claimed as browser-verified.
- `npm audit` reports **65 findings** (43 moderate, 16 high, 6 critical) in the retained dependency tree, including unused existing packages. No broad or breaking dependency upgrades were attempted as part of the redesign. Review that tooling before public deployment.

## Remaining editorial and launch work

1. Add snippets, assign categories, write independent teasers, choose languages and add Matrix content. No sample articles are published.
2. Edit the homepage headline/intro, category introductions/artwork and featured selections.
3. Confirm publication language and timezone: the supplied blueprint currently has site language `de` and timezone `America/Los_Angeles`; the interface copy is English.
4. Add real legal/contact pages if needed. Newsletter signup remains intentionally absent until a backend exists.
5. Perform desktop/tablet/mobile visual and accessibility review, and review the existing dependency audit findings.
6. Apply Project Config and build assets in deployment, configure the live URL/environment and persist uploads. Confirm production SEO/robots and sitemap after entering content.
