<?php
/** Integration check: fixtures are rolled back, including on failure. Run with DDEV. */
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__) . '/web/index.php';
$_SERVER['HTTP_HOST'] = 'marukka.ch.ddev.site';
$_SERVER['REQUEST_URI'] = '/snippets';
$_SERVER['SERVER_PORT'] = '443';
$_SERVER['HTTPS'] = 'on';
require dirname(__DIR__) . '/bootstrap.php';
$app = require CRAFT_VENDOR_PATH . '/craftcms/cms/bootstrap/web.php';
$app->view->setTemplateMode(craft\web\View::TEMPLATE_MODE_SITE);
$transaction = $app->db->beginTransaction();
function check(bool $ok, string $message): void {
    if (!$ok) throw new RuntimeException($message);
    echo "PASS $message\n";
}
function saveEntry($entry): void {
    if (!Craft::$app->elements->saveElement($entry)) throw new RuntimeException(json_encode($entry->getErrors()));
}
try {
    $service = $app->entries;
    $section = $service->getSectionByHandle('snippets');
    $type = $section->getEntryTypes()[0];
    $category = craft\elements\Entry::find()->section('categories')->slug('craft')->one();
    $matrix = $app->fields->getFieldByHandle('contentMatrix');
    $codeType = current(array_filter($service->getAllEntryTypes(), fn($t) => $t->handle === 'code'));
    $textType = current(array_filter($service->getAllEntryTypes(), fn($t) => $t->handle === 'text'));
    $rawCode = "<script>alert(\"not HTML\")</script>\n    const answer = 42;\n";
    $fixtures = [];
    for ($i = 0; $i < 14; $i++) {
        $entry = new craft\elements\Entry(['sectionId' => $section->id, 'typeId' => $type->id, 'title' => 'Validation fixture ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT), 'slug' => 'validation-only-' . $i]);
        $entry->setFieldValues(['lead' => 'A test introduction.', 'kategorie' => [$category->id], 'teaser' => $i === 2 ? '' : ($i % 2 === 0 ? $rawCode : '<b>This stays plain text.</b>'), 'teaserIsCode' => $i % 2 === 0, 'teaserLanguage' => 'javascript', 'featured' => $i === 0, 'readingMinutes' => 2]);
        saveEntry($entry);
        $fixtures[] = $entry;
    }
    $article = $fixtures[0];
    foreach ([[$codeType, ['code' => $rawCode, 'language' => 'javascript']], [$textType, ['richText' => '<h2>Practical example</h2><p>Readable article content.</p>']]] as [$blockType, $values]) {
        $block = new craft\elements\Entry(['typeId' => $blockType->id, 'fieldId' => $matrix->id, 'ownerId' => $article->id, 'title' => $blockType->name]);
        $block->setFieldValues($values);
        saveEntry($block);
    }
    $view = $app->view;
    $archive = $view->renderTemplate('_pages/archive');
    check(substr_count($archive, 'class="snippet-card"') === 12, 'Archive paginates at 12 entries');
    check(str_contains($archive, 'aria-label="Next page"'), 'Pagination provides the next-page link');
    check(str_contains($archive, '&lt;script&gt;'), 'Code teasers escape HTML');
    check(!str_contains($archive, '<script>alert('), 'Code cannot execute as HTML');
    check(str_contains($archive, '&lt;b&gt;This stays plain text.&lt;/b&gt;'), 'Text teasers escape HTML');
    check(str_contains($archive, 'A test introduction.'), 'Empty teaser falls back to lead');
    $detail = $view->renderTemplate('_pages/article', ['entry' => craft\elements\Entry::find()->id($article->id)->one()]);
    file_put_contents(Craft::getAlias('@storage/runtime/marukka-validation-article.html'), $detail);
    check(str_contains($detail, 'Practical example'), 'Article renders Matrix rich text');
    check(str_contains($detail, 'x-data="codeCopy"'), 'Article code exposes copy controls');
    check(str_contains($detail, "&lt;/script&gt;\n    const answer = 42;\n"), 'Article code preserves whitespace and escapes HTML');
    check(str_contains($detail, 'Related snippets'), 'Related snippets use real category relations');
    $app->request->setQueryParams(['q' => 'validation', 'sort' => 'title']);
    $search = $view->renderTemplate('_pages/archive');
    check(str_contains($search, 'Search results'), 'Archive search renders');
    $app->request->setQueryParams([]);
    $home = $view->renderTemplate('_pages/homepage', ['entry' => craft\elements\Entry::find()->section('home')->one()]);
    check(substr_count($home, 'class="snippet-card"') === 1, 'Homepage honors featured selection');
    check(substr_count($home, 'class="category-card"') === 6, 'Homepage renders six real categories');
    $layout = $type->getFieldLayout();
    foreach ($layout->getCustomFieldElements() as $element) {
        if ($element->getField()->handle === 'teaserLanguage') {
            $condition = $element->getElementCondition();
            check($condition !== null && $condition->matchElement($fixtures[0]) && !$condition->matchElement($fixtures[1]), 'Language field appears only for code teasers');
        }
    }
    foreach (glob(dirname(__DIR__) . '/templates/{_pages,_components,_blocks,_includes,_layouts,_errors,_routers}/*.twig', GLOB_BRACE) as $file) {
        $view->getTwig()->parse($view->getTwig()->tokenize(new Twig\Source(file_get_contents($file), $file)));
    }
    check(true, 'All Twig templates compile');
} catch (Throwable $e) {
    echo 'FAIL ' . $e->getMessage() . PHP_EOL;
    throw $e;
} finally {
    $transaction->rollBack();
    echo "Validation fixtures rolled back.\n";
}
