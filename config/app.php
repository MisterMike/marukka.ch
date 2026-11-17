<?php

use craft\helpers\App;

return [
    'id' => App::env('CRAFT_APP_ID') ?: 'CraftCMS',
    'modules' => [
        'sitemodule' => [
            'class' => \modules\sitemodule\SiteModule::class,
        ]
    ],
    'bootstrap' => ['sitemodule'],
];