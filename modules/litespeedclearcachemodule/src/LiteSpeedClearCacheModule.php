<?php
/**
 * LiteSpeedClearCache module for Craft CMS 3.x
 *
 * clear lite speed cache
 *
 * @link      https://kreisivier.ch
 * @copyright Copyright (c) 2019 Pascal Ujak
 */

namespace modules\litespeedclearcachemodule;

use modules\litespeedclearcachemodule\assetbundles\litespeedclearcachemodule\LiteSpeedClearCacheModuleAsset;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;
use craft\services\Elements;

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

/**
 * Class LiteSpeedClearCacheModule
 *
 * @author    Pascal Ujak
 * @package   LiteSpeedClearCacheModule
 * @since     1.0.
 *
 */
class LiteSpeedClearCacheModule extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * @var LiteSpeedClearCacheModule
     */
    public static $instance;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/litespeedclearcachemodule', $this->getBasePath());
        $this->controllerNamespace = 'modules\litespeedclearcachemodule\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/litespeedclearcachemodule/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Base template directory
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function (RegisterTemplateRootsEvent $e) {
            if (is_dir($baseDir = $this->getBasePath().DIRECTORY_SEPARATOR.'templates')) {
                $e->roots[$this->id] = $baseDir;
            }
        });

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

      Event::on(
        Elements::class,
        Elements::EVENT_AFTER_SAVE_ELEMENT,
        function(Event $event) {
          header('X-LiteSpeed-Purge: tag=K4');
        });

      
        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {
                    try {
                        Craft::$app->getView()->registerAssetBundle(LiteSpeedClearCacheModuleAsset::class);
                    } catch (InvalidConfigException $e) {
                        Craft::error(
                            'Error registering AssetBundle - '.$e->getMessage(),
                            __METHOD__
                        );
                    }
                }
            );
        }

        Craft::info(
            Craft::t(
                'lite-speed-clear-cache-module',
                '{name} module loaded',
                ['name' => 'LiteSpeedClearCache']
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================
}
