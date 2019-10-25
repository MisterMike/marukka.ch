<?php
/**
 * General Configuration
 *
 * (c) 2017 - 2019 kreisvier Communications AG
 *
 * @see craft\config\GeneralConfig
 */

return [
    // Global settings
    '*' => [

        'defaultWeekStartDay' => 1,

        'enableCsrfProtection' => true,

        'omitScriptNameInUrls' => true,

        'cpTrigger' => 'admin',

        'securityKey' => getenv('SECURITY_KEY'),
        
        'useProjectConfigFile' => false,
        
        'convertFilenamesToAscii' => true,

        'limitAutoSlugsToAscii' => true,

        'errorTemplatePrefix' => "_errors/",

        'enableTemplateCaching' => false,

        // globally turn off devMode (default)
        'devMode' => false,

        // Custom site-specific config settings
        'custom' => [
            'craftEnv' => CRAFT_ENVIRONMENT,
            'staticAssetsVersion' => time(),
        ]
    ],

    'dev' => [
        // Base site URL
        'siteUrl' => getenv('DEFAULT_SITE_URL'),

        'devMode' => true,
    ],

    'staging' => [

        'siteUrl' => 'https://craft.kitesailing.ch',
        'devMode' => true,
    ],

    'production' => [

        'siteUrl' => 'https://www.kitesailing.ch',
    ],
];
