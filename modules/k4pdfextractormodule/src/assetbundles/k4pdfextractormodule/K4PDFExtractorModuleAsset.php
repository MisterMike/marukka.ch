<?php
/**
 * k4PDFExtractor module for Craft CMS 3.x
 *
 * Extracts Content from PDF Files
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 kreisvier communications ag
 */

namespace modules\k4pdfextractormodule\assetbundles\K4PDFExtractorModule;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    kreisvier communications ag
 * @package   K4PDFExtractorModule
 * @since     1
 */
class K4PDFExtractorModuleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@modules/k4pdfextractormodule/assetbundles/k4pdfextractormodule/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/K4PDFExtractorModule.js',
        ];

        $this->css = [
            'css/K4PDFExtractorModule.css',
        ];

        parent::init();
    }
}
