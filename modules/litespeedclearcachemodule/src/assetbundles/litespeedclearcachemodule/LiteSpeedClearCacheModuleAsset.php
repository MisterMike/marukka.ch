<?php
/**
 * LiteSpeedClearCache module for Craft CMS 3.x
 *
 * clear lite speed cache
 *
 * @link      https://kreisivier.ch
 * @copyright Copyright (c) 2019 Pascal Ujak
 */

namespace modules\litespeedclearcachemodule\assetbundles\LiteSpeedClearCacheModule;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Pascal Ujak
 * @package   LiteSpeedClearCacheModule
 * @since     1.0.
 */
class LiteSpeedClearCacheModuleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@modules/litespeedclearcachemodule/assetbundles/litespeedclearcachemodule/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/LiteSpeedClearCacheModule.js',
        ];

        $this->css = [
            'css/LiteSpeedClearCacheModule.css',
        ];

        parent::init();
    }
}
