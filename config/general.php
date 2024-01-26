<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

use craft\config\GeneralConfig;
use craft\helpers\App;

$isDev = App::env('ENVIRONMENT') === 'dev';
$isProd = App::env('ENVIRONMENT') === 'production';

return GeneralConfig::create()
    // Craft config settings from .env variables
    ->aliases([
        '@web' => App::env('PRIMARY_SITE_URL'),
        '@webroot' => App::env('WEB_ROOT_PATH'),
        '@assetsUrl' => App::env('PRIMARY_SITE_URL').'/assets',
        '@assetsRootUrl' => App::env('WEB_ROOT_PATH').'/assets'
    ])
    ->allowUpdates(App::env('ALLOW_UPDATES'))
    ->devMode($isDev)
    ->cpTrigger(App::env('CP_TRIGGER') ?: 'admin')
    ->defaultWeekStartDay(1)
    ->enableTemplateCaching(!$isDev)
    ->securityKey(App::env('SECURITY_KEY'))
    ->limitAutoSlugsToAscii(true)

    // Craft config settings from constants
    ->cacheDuration(false)
    ->defaultSearchTermOptions([
        'subLeft' => true,
        'subRight' => true,
    ])
    ->defaultTokenDuration('P2W')
    ->enableCsrfProtection(true)
    ->errorTemplatePrefix('_errors/')
    ->maxUploadFileSize('100M')
    ->extraFileKinds([
        'text' => [
            'extensions' => ['csv'],
        ],
    ])
    ->generateTransformsBeforePageLoad(!$isDev)
    ->maxCachedCloudImageSize(3000)
    ->maxUploadFileSize('100M')
    ->omitScriptNameInUrls(true)
    ->useEmailAsUsername(true)
    ->disallowRobots(!$isProd)
    ->usePathInfo(true);
