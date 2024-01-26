<?php
/**
 * k4TwigExtensions module for Craft CMS 3.x
 *
 * Installs various twig extensions
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 kreisvier communications AG
 * @author    Mischa Sprecher, kreisvier communications AG
 * @package   k4TwigExtensionsModule
 * @since     1
 */
namespace modules\k4twigextensionsmodule;

use Craft;
use modules\k4twigextensionsmodule\twigextensions\ShopExtension;
use yii\base\Module;
use craft\i18n\PhpMessageSource;
use modules\k4twigextensionsmodule\twigextensions\DeDuplicateExtension;
use modules\k4twigextensionsmodule\twigextensions\ThumbalizerExtension;
use modules\k4twigextensionsmodule\twigextensions\ClassExtension;
use modules\k4twigextensionsmodule\twigextensions\StringExtension;
use modules\k4twigextensionsmodule\twigextensions\SlugExtension;
use modules\k4twigextensionsmodule\twigextensions\DumpDieExtension;
use modules\k4twigextensionsmodule\twigextensions\Rot13Extension;
use modules\k4twigextensionsmodule\twigextensions\RelativeDateExtension;
use modules\k4twigextensionsmodule\twigextensions\GlobalVariablesExtension;
use modules\k4twigextensionsmodule\twigextensions\RegExpExtension;

class k4TwigExtensionsModule extends Module
{
    /**
     * @var k4TwigExtensionsModule
     */
    public static $instance;

    /**
     * The available extensions
     *
     * @var array
     */
    public $extensions = [
        DeDuplicateExtension::class,
        Rot13Extension::class,
        GlobalVariablesExtension::class,
        RelativeDateExtension::class,
        ClassExtension::class,
        ThumbalizerExtension::class,
        StringExtension::class,
        RegExpExtension::class,
        DumpDieExtension::class,
        SlugExtension::class
    ];

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/k4twigextensionsmodule', $this->getBasePath());
        $this->controllerNamespace = 'modules\k4twigextensionsmodule\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'de-CH',
                'basePath' => '@modules/k4twigextensionsmodule/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    public function init()
    {
        parent::init();
        self::$instance = $this;

        $settings = Craft::$app->config->getConfigFromFile('k4twigextensions');

        $this->registerExtensions(
            array_diff($this->extensions, $settings['disabled'] ?? [])
        );

    }

    /**
     * Register twig extensions
     *
     * @param array $extensions
     */
    private function registerExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            Craft::$app->view->registerTwigExtension(new $extension);
        }
    }


}
