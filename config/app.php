<?php
/**
 * Yii Application Config
 *
 * Edit this file at your own risk!
 *
 * The array returned by this file will get merged with
 * vendor/craftcms/cms/src/config/app.php and app.[web|console].php, when
 * Craft's bootstrap script is defining the configuration for the entire
 * application.
 *
 * You can define custom modules and system components, and even override the
 * built-in system components.
 *
 * If you want to modify the application config for *only* web requests or
 * *only* console requests, create an app.web.php or app.console.php file in
 * your config/ folder, alongside this one.
 */

return [
  'modules' => [
    'k4-site-module' => [
      'class' => \modules\k4sitemodule\K4SiteModule::class,
    ],
    'k4-pdfextractor-module' => [
      'class' => \modules\k4pdfextractormodule\K4PDFExtractorModule::class,
    ],
    'k4-twig-extensions-module' => [
        'class' => \modules\k4twigextensionsmodule\K4TwigExtensionsModule::class,
    ]
  ],
  'bootstrap' => ['k4-site-module','k4-pdfextractor-module'],
];
