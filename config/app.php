<?php
use craft\helpers\App;
use modules\foobar\Module as ModuleAlias;

return [
    'id' => App::env('APP_ID') ?: 'CraftCMS',
    'modules' => [
        'k4twigextensionsmodule' => [
            'class' => \modules\k4twigextensionsmodule\k4TwigExtensionsModule::class,
        ],
        'site-module' => [
            'class' => \modules\sitemodule\SiteModule::class,
        ]
    ],
    'bootstrap' => ['site-module', 'k4twigextensionsmodule'],
    'components' => [
        'deprecator' => [
            'throwExceptions' => YII_DEBUG,
        ],
    ]
];