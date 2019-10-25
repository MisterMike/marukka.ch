<?php
/**
 * k4PDFExtractor module for Craft CMS 3.x
 *
 * Extracts Content from PDF Files
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 kreisvier communications ag
 */

namespace modules\k4pdfextractormodule;

use modules\k4pdfextractormodule\assetbundles\k4pdfextractormodule\K4PDFExtractorModuleAsset;
use modules\k4pdfextractormodule\twigextensions\K4PDFExtractorModuleTwigExtension;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

/**
 * Class K4PDFExtractorModule
 *
 * @author    kreisvier communications ag
 * @package   K4PDFExtractorModule
 * @since     1
 *
 */
class K4PDFExtractorModule extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * @var K4PDFExtractorModule
     */
    public static $instance;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/k4pdfextractormodule', $this->getBasePath());
        $this->controllerNamespace = 'modules\k4pdfextractormodule\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/k4pdfextractormodule/translations',
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

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {
                    try {
                        Craft::$app->getView()->registerAssetBundle(K4PDFExtractorModuleAsset::class);
                    } catch (InvalidConfigException $e) {
                        Craft::error(
                            'Error registering AssetBundle - '.$e->getMessage(),
                            __METHOD__
                        );
                    }
                }
            );
        }

        Craft::$app->view->registerTwigExtension(new K4PDFExtractorModuleTwigExtension());

        Craft::info(
            Craft::t(
                'k4-pdfextractor-module',
                '{name} module loaded',
                ['name' => 'k4PDFExtractor']
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================
}
