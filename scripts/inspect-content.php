<?php
require dirname(__DIR__) . '/bootstrap.php';
$app = require CRAFT_VENDOR_PATH . '/craftcms/cms/bootstrap/console.php';
foreach ($app->entries->getAllSections() as $section) {
    echo json_encode(['section' => $section->handle, 'uid' => $section->uid, 'type' => $section->type, 'sites' => array_map(fn($s) => ['uriFormat' => $s->uriFormat, 'template' => $s->template], $section->getSiteSettings())]) . PHP_EOL;
}
foreach ($app->entries->getAllEntryTypes() as $type) {
    echo json_encode(['entryType' => $type->handle, 'uid' => $type->uid, 'fields' => array_map(fn($f) => ['handle' => $f->handle, 'type' => get_class($f), 'uid' => $f->uid], $type->getFieldLayout()->getCustomFields())]) . PHP_EOL;
}
echo 'ENTRIES ' . craft\elements\Entry::find()->count() . PHP_EOL;
foreach (craft\elements\Entry::find()->limit(8)->all() as $entry) {
    echo json_encode(['title' => $entry->title, 'section' => $entry->section->handle, 'url' => $entry->url]) . PHP_EOL;
}
