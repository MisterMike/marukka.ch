<?php
/**
 * Site Module module for Craft CMS 4.x
 *
 * A simple Module to enhance your site
 *
 * @copyright Copyright (c) 2019-23 Mischa Sprecher
 */

namespace modules\sitemodule\assetbundles\sitemodule;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * SiteModuleAsset AssetBundle
 * @author    Mischa Sprecher
 * @package   K4SiteModule
 * @since     1.0.0
 */
class SiteModuleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@modules/sitemodule/assetbundles/sitemodule/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/SiteModule.js',
        ];

        $this->css = [
            'css/SiteModule.css',
        ];

        parent::init();
    }
}
