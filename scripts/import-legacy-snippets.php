<?php
/** Import the article-only JSON export through Craft APIs. Dry-run by default. */
require dirname(__DIR__) . '/bootstrap.php';
$app = require CRAFT_VENDOR_PATH . '/craftcms/cms/bootstrap/console.php';

use craft\elements\Entry;

function importRequire(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}
function importSave(Entry $entry): void {
    if (!Craft::$app->elements->saveElement($entry, updateSearchIndex: true)) {
        throw new RuntimeException(json_encode($entry->getErrors(), JSON_UNESCAPED_UNICODE));
    }
}
function utcDate(string $date): DateTime {
    return new DateTime($date, new DateTimeZone('UTC'));
}
function textContent(string $html): string {
    return html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
function tableRows(string $html): int {
    return preg_match_all('/<tr\b/i', $html);
}
function preformattedText(string $html): array {
    preg_match_all('/<pre\b[^>]*>(.*?)<\/pre>/is', $html, $matches);
    return array_map('textContent', $matches[1]);
}

$path = $argv[1] ?? null;
importRequire($path !== null && is_file($path), 'Usage: php scripts/import-legacy-snippets.php export.json [--apply]');
$apply = in_array('--apply', $argv, true);
$plan = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
importRequire(($plan['format'] ?? null) === 'marukka-legacy-snippets' && ($plan['version'] ?? null) === 1, 'Unknown export format');
importRequire(count($plan['articles']) === $plan['summary']['entries'], 'Manifest count mismatch');
$section = $app->entries->getSectionByHandle('snippets');
importRequire($section !== null, 'Missing target snippets section');
$type = $section->getEntryTypes()[0];
importRequire($type->handle === 'snippet', 'Unexpected target entry type');
$siteId = $app->sites->getPrimarySite()->id;
$matrix = $app->fields->getFieldByHandle('contentMatrix');
$blockTypes = [];
foreach ($matrix->getEntryTypes() as $blockType) $blockTypes[$blockType->handle] = $blockType;
importRequire(isset($blockTypes['text'], $blockTypes['code']), 'Missing target Matrix entry types');
$categories = [];
foreach (Entry::find()->section('categories')->siteId($siteId)->status(null)->all() as $category) $categories[$category->slug] = $category;

// Preflight the whole plan before changing any entries.
$existing = [];
$sourceUids = [];
foreach ($plan['articles'] as $article) {
    importRequire(!isset($sourceUids[$article['sourceUid']]), 'Duplicate source UID');
    $sourceUids[$article['sourceUid']] = true;
    foreach ($article['categories'] as $slug) importRequire(isset($categories[$slug]), "Missing target category $slug");
    $match = Entry::find()->uid($article['sourceUid'])->siteId($siteId)->status(null)->trashed(null)->one();
    if ($match) {
        importRequire($match->sectionId === $section->id, 'Source UID already belongs to another target section');
        $existing[$article['sourceUid']] = $match;
        continue;
    }
    $collision = Entry::find()->section('snippets')->slug($article['slug'])->siteId($siteId)->status(null)->trashed(null)->exists();
    importRequire(!$collision, "Target slug collision: {$article['slug']}");
}

$report = [
    'applied' => $apply, 'sourceSha256' => $plan['sourceSha256'],
    'sourceCraftVersion' => $plan['sourceCraftVersion'],
    'targetCraftVersion' => Craft::$app->version,
    'created' => 0, 'skipped' => 0, 'blocksCreated' => 0, 'articles' => [],
];
$transaction = $app->db->beginTransaction();
try {
    foreach ($plan['articles'] as $article) {
        if (isset($existing[$article['sourceUid']])) {
            $entry = $existing[$article['sourceUid']];
            $report['skipped']++;
            $report['articles'][] = [
                'sourceId' => $article['sourceId'], 'targetId' => $entry->id,
                'title' => $entry->title, 'action' => 'skipped-existing',
                'note' => 'Existing content, enablement and editorial changes left untouched.',
                'editUrl' => $entry->getCpEditUrl(),
                'legacyPath' => $article['legacyPath'], 'targetPath' => '/snippets/' . $entry->slug,
                'legacySeo' => $article['legacySeo'], 'review' => $article['review'],
            ];
            continue;
        }
        $entry = new Entry([
            'sectionId' => $section->id, 'typeId' => $type->id, 'siteId' => $siteId,
            'uid' => $article['sourceUid'], 'title' => $article['title'], 'slug' => $article['slug'],
            'enabled' => false, 'enabledForSite' => true,
            'postDate' => utcDate($article['postDate']), 'dateCreated' => utcDate($article['dateCreated']),
        ]);
        $entry->setFieldValues([
            'lead' => $article['lead'],
            'kategorie' => array_map(fn($slug) => $categories[$slug]->id, $article['categories']),
            'teaser' => null, 'teaserIsCode' => false, 'featured' => false,
        ]);
        importSave($entry);
        $blockIds = [];
        foreach ($article['blocks'] as $index => $source) {
            importRequire(in_array($source['type'], ['text', 'code'], true), 'Unsupported target block type');
            $block = new Entry([
                'typeId' => $blockTypes[$source['type']]->id, 'fieldId' => $matrix->id,
                'ownerId' => $entry->id, 'siteId' => $siteId, 'sortOrder' => $index + 1,
                'title' => $source['type'] === 'code' ? 'Code' : 'Text',
                'enabled' => true, 'enabledForSite' => true,
            ]);
            $block->setFieldValues($source['type'] === 'code'
                ? ['code' => $source['code'], 'language' => $source['language']]
                : ['richText' => $source['html']]);
            importSave($block);
            $blockIds[] = $block->id;
            $report['blocksCreated']++;
        }
        // Reload through real element queries; compare stored values rather than input objects.
        $saved = Entry::find()->id($entry->id)->siteId($siteId)->status(null)->one();
        importRequire($saved !== null && !$saved->enabled, 'Imported snippet must remain disabled');
        importRequire($saved->slug === $article['slug'], 'Craft changed a source slug');
        importRequire((clone $saved->postDate)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s') === $article['postDate'], 'Publication date changed');
        importRequire(!$saved->teaser && !$saved->teaserIsCode, 'Teaser must remain independently authored');
        $savedBlocks = $saved->contentMatrix->all();
        importRequire(count($savedBlocks) === count($article['blocks']), 'Stored Matrix block count differs');
        foreach ($savedBlocks as $index => $block) {
            $source = $article['blocks'][$index];
            importRequire($block->id === $blockIds[$index] && $block->type->handle === $source['type'], 'Matrix order changed');
            if ($source['type'] === 'code') {
                importRequire($block->code === $source['code'], 'Stored code differs from the source');
                importRequire((string)$block->language === $source['language'], 'Stored code language changed');
            } else {
                $stored = (string)$block->richText;
                importRequire(tableRows($stored) === tableRows($source['html']), 'HTML table rows changed');
                importRequire(preformattedText($stored) === preformattedText($source['html']), 'Embedded code whitespace changed');
                // Sanitization may normalize markup and whitespace, but must keep the words and code.
                $normalize = fn($value) => preg_replace('/\s+/u', '', textContent($value));
                importRequire($normalize($stored) === $normalize($source['html']), 'Rich-text content was lost during sanitization');
            }
        }
        importRequire(!Entry::find()->section('snippets')->id($entry->id)->exists(), 'Imported snippet is publicly visible');
        $report['created']++;
        $report['articles'][] = [
            'sourceId' => $article['sourceId'], 'targetId' => $saved->id,
            'title' => $saved->title, 'action' => 'created-disabled', 'blocks' => count($savedBlocks),
            'categories' => $article['categories'], 'editUrl' => $saved->getCpEditUrl(),
            'legacyPath' => $article['legacyPath'], 'targetPath' => '/snippets/' . $saved->slug,
            'legacySeo' => $article['legacySeo'], 'review' => $article['review'],
        ];
        echo "Validated disabled snippet: {$saved->title}\n";
    }
    if ($apply) $transaction->commit();
    else $transaction->rollBack();
} catch (Throwable $e) {
    if ($transaction->isActive) $transaction->rollBack();
    fwrite(STDERR, 'Import rolled back: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
$reportPath = dirname($path) . ($apply ? '/legacy-import-result.json' : '/legacy-import-dry-run.json');
file_put_contents($reportPath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
echo ($apply ? 'APPLIED' : 'DRY RUN — rolled back') . ": {$report['created']} created, {$report['skipped']} skipped, {$report['blocksCreated']} blocks.\n";
echo "Report: $reportPath\n";
