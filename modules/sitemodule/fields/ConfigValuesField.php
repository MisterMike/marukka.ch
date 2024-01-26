<?php
/**
 * Site Module module for Craft CMS 4.x
 *
 * A simple Module to enhance your site
 *
 * @copyright Copyright (c) 2019-23 Mischa Sprecher
 */

namespace modules\sitemodule\fields;

use modules\sitemodule\models\SettingsModel;
use modules\sitemodule\SiteModule;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Statik.be
 * @package   ConfigValuesField
 * @since     1.0.0
 *
 * @property-read string $contentColumnType
 * @property-read bool|array $options
 * @property-read string $settingsHtml
 */
class ConfigValuesField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $dataSet = '';
    public string $type = 'dropdown';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('site-module', 'Globales Auswahlfeld');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['dataSet', 'string'],
            ['dataSet', 'required'],
            ['type', 'string'],
            ['type', 'required'],
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null): mixed
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null): mixed
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): string
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'site-module/_components/fields/ConfigValuesFieldField_settings',
            [
                'data' => $this->getOptions(),
                'type' => $this->type,
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);

        // prepare data
        $optionsFile = Craft::$app->getConfig()->getConfigFromFile('site-module');
        $options = $optionsFile['data'][$this->dataSet];
        //$keys = array_keys($options);


        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'site-module/_components/fields/ConfigValuesFieldField_input',
            [
                'name' => $this->handle,
                'type' => $this->type,
                'options' => $options,
                'value' => $value,
                'field' => $this,
                'id' => $id,
            ]
        );
    }


    /**
     * Get our dataset options from the 'data' array key in config/site-module
     * @return bool|array
     */
    private function getOptions(): bool|array
    {
        $configFile = Craft::$app->getConfig()->getConfigFromFile('site-module');

        $keys = array_keys($configFile['data']);

        if (!$keys) {
            return false;
        }
        $data[] = '---';

        foreach ($keys as $key) {
            $data[$key] = $key;
        }

        return $data;
    }
}
