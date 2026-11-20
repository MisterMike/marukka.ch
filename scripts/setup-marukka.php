<?php
/** Bootstrap the empty blueprint through Craft's APIs; writes normal Project Config. */
require dirname(__DIR__) . '/bootstrap.php';
$app = require CRAFT_VENDOR_PATH . '/craftcms/cms/bootstrap/console.php';

use craft\elements\Entry;
use craft\fieldlayoutelements\CustomField;
use craft\fieldlayoutelements\entries\EntryTitleField;
use craft\models\EntryType;
use craft\models\FieldLayout;
use craft\models\FieldLayoutTab;
use craft\models\Section;
use craft\models\Section_SiteSettings;

function saveOrFail($service, string $method, $model): void {
    if (!$service->$method($model)) {
        throw new RuntimeException(json_encode($model->getErrors()));
    }
}
function field(string $handle, string $name, string $class, array $settings = []) {
    $fields = Craft::$app->fields;
    $existing = $fields->getFieldByHandle($handle);
    if ($existing && get_class($existing) === $class) return $existing;
    if ($existing) {
        $new = new $class(array_merge($existing->getSettings(), ['id' => $existing->id, 'uid' => $existing->uid, 'handle' => $handle, 'name' => $name], $settings));
        saveOrFail($fields, 'saveField', $new);
        return $new;
    }
    $new = new $class(array_merge(['handle' => $handle, 'name' => $name], $settings));
    saveOrFail($fields, 'saveField', $new);
    return $new;
}
function layoutField(string $handle, array $config = []): CustomField {
    return new CustomField(Craft::$app->fields->getFieldByHandle($handle), $config);
}
function entryType(string $handle, string $name, array $elements): EntryType {
    foreach (Craft::$app->entries->getAllEntryTypes() as $existing) {
        if ($existing->handle === $handle) {
            $layout = $existing->getFieldLayout();
            $handles = array_map(fn($f) => $f->handle, $layout->getCustomFields());
            $missing = array_filter($elements, fn($element) => !in_array($element->handle ?? $element->getField()->handle, $handles, true));
            if ($missing) {
                $tabs = $layout->getTabs();
                $tabs[0]->setElements(array_merge($tabs[0]->getElements(), $missing));
                saveOrFail(Craft::$app->entries, 'saveEntryType', $existing);
            }
            return $existing;
        }
    }
    $type = new EntryType(['handle' => $handle, 'name' => $name]);
    $layout = new FieldLayout(['type' => Entry::class]);
    $layout->setTabs([['name' => 'Content', 'elements' => array_merge([new EntryTitleField()], $elements)]]);
    $type->setFieldLayout($layout);
    saveOrFail(Craft::$app->entries, 'saveEntryType', $type);
    return $type;
}
function section(string $handle, string $name, string $kind, EntryType $type, string $uri, string $template): Section {
    $existing = Craft::$app->entries->getSectionByHandle($handle);
    if ($existing) return $existing;
    $siteId = Craft::$app->sites->getPrimarySite()->id;
    $section = new Section(['handle' => $handle, 'name' => $name, 'type' => $kind]);
    $section->setEntryTypes([$type]);
    $section->setSiteSettings([$siteId => new Section_SiteSettings(['siteId' => $siteId, 'hasUrls' => true, 'uriFormat' => $uri, 'template' => $template])]);
    saveOrFail(Craft::$app->entries, 'saveSection', $section);
    return $section;
}

field('teaser', 'Teaser', modules\sitemodule\fields\WhitespaceText::class, ['multiline' => true, 'searchable' => true, 'instructions' => 'A short excerpt or manually selected code. Independent of article content.']);
field('textPlainMultiline', 'Text Plain Multiline', modules\sitemodule\fields\WhitespaceText::class, ['multiline' => true, 'searchable' => true]);
$isCode = field('teaserIsCode', 'Teaser is code', craft\fields\Lightswitch::class);
field('teaserLanguage', 'Code language', craft\fields\Dropdown::class, ['options' => array_map(fn($value, $label) => ['label' => $label, 'value' => $value, 'default' => $value === 'plaintext'], ['plaintext', 'javascript', 'typescript', 'php', 'twig', 'css', 'xml', 'json', 'bash', 'sql', 'yaml'], ['Plain text', 'JavaScript', 'TypeScript', 'PHP', 'Twig', 'CSS', 'HTML / XML', 'JSON', 'Shell', 'SQL', 'YAML'])]);
field('lead', 'Introduction', craft\fields\PlainText::class, ['multiline' => true, 'searchable' => true]);
field('featured', 'Featured snippet', craft\fields\Lightswitch::class);
field('readingMinutes', 'Reading time (minutes)', craft\fields\Number::class, ['min' => 1, 'decimals' => 0]);

foreach (['bilderLokal' => 'images', 'dokumenteLokal' => 'documents'] as $handle => $folder) {
    if (!$app->fs->getFilesystemByHandle($handle)) {
        $fs = new craft\fs\Local(['name' => ucfirst($folder), 'handle' => $handle, 'hasUrls' => true, 'url' => '@web/uploads/' . $folder, 'path' => '@webroot/uploads/' . $folder]);
        saveOrFail($app->fs, 'saveFilesystem', $fs);
    }
}

$text = entryType('text', 'Text', [layoutField('richText')]);
$richText = $app->fields->getFieldByHandle('richText');
if ($richText instanceof craft\ckeditor\Field && $richText->headingLevels !== [2, 3, 4]) {
    $richText->headingLevels = [2, 3, 4];
    saveOrFail($app->fields, 'saveField', $richText);
}
if ($richText instanceof craft\ckeditor\Field) {
    $needed = ['underline', 'bulletedList', 'numberedList', 'code', 'codeBlock', 'insertTable', 'sourceEditing'];
    $missing = array_values(array_diff($needed, $richText->toolbar));
    if ($missing) {
        $richText->toolbar = [...$richText->toolbar, '|', ...$missing];
        saveOrFail($app->fields, 'saveField', $richText);
    }
}
$code = entryType('code', 'Code', [layoutField('textPlainMultiline', ['handle' => 'code', 'label' => 'Code']), layoutField('teaserLanguage', ['handle' => 'language', 'label' => 'Language'])]);
$image = entryType('image', 'Image', [layoutField('assetSingle', ['handle' => 'image', 'label' => 'Image']), layoutField('plainText', ['handle' => 'caption', 'label' => 'Caption / alternative text'])]);
$matrix = $app->fields->getFieldByHandle('contentMatrix');
// The supplied blueprint refers to removed entry types. Configure only while unused.
if (!Entry::find()->fieldId($matrix->id)->status(null)->exists()) {
    $matrix->setEntryTypes([$text, $code, $image]);
    saveOrFail($app->fields, 'saveField', $matrix);
}

$categoryType = entryType('category', 'Category', [layoutField('lead'), layoutField('assetSingle', ['handle' => 'categoryImage', 'label' => 'Category artwork (optional)'])]);
$categorySection = section('categories', 'Categories', Section::TYPE_CHANNEL, $categoryType, 'topics/{slug}', '_pages/category');
$categoryField = $app->fields->getFieldByHandle('kategorie');
$categoryField->sources = ['section:' . $categorySection->uid];
saveOrFail($app->fields, 'saveField', $categoryField);

$language = layoutField('teaserLanguage', ['elementCondition' => [
    'class' => craft\elements\conditions\entries\EntryCondition::class,
    'elementType' => Entry::class,
    'conditionRules' => [['class' => craft\fields\conditions\LightswitchFieldConditionRule::class, 'fieldUid' => $isCode->uid, 'value' => true]],
]]);
$snippet = entryType('snippet', 'Snippet', [layoutField('lead'), layoutField('kategorie'), layoutField('teaser'), layoutField('teaserIsCode'), $language, layoutField('featured'), layoutField('readingMinutes'), layoutField('assetSingle', ['handle' => 'heroImage', 'label' => 'Hero image']), layoutField('contentMatrix')]);
section('snippets', 'Snippets', Section::TYPE_CHANNEL, $snippet, 'snippets/{slug}', '_pages/article');
$home = entryType('homepage', 'Homepage', [layoutField('plainText', ['handle' => 'heroTitle', 'label' => 'Hero headline']), layoutField('lead'), layoutField('contentMatrix')]);
section('home', 'Homepage', Section::TYPE_SINGLE, $home, '__home__', '_pages/homepage');

// Taxonomy only; no sample articles or imported content.
if (in_array('--categories', $argv, true)) {
    foreach (['craft' => 'Craft CMS', 'twig' => 'Twig', 'css' => 'CSS', 'laravel' => 'Laravel', 'mac' => 'Mac', 'javascript' => 'JavaScript'] as $slug => $title) {
        if (Entry::find()->section('categories')->slug($slug)->status(null)->exists()) continue;
        $entry = new Entry(['sectionId' => $categorySection->id, 'typeId' => $categoryType->id, 'title' => $title, 'slug' => $slug]);
        saveOrFail($app->elements, 'saveElement', $entry);
    }
}
$app->projectConfig->flush();
echo "Marukka content model is ready. Project Config written by Craft.\n";
