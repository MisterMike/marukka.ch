<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 * @link https://craftcms.com/docs/5.x/reference/config/general.html
 */

use craft\config\GeneralConfig;
use craft\helpers\App;

$environment = App::env('CRAFT_ENVIRONMENT') ?? App::env('ENVIRONMENT');
$isDev = $environment === 'dev';
$isProd = $environment === 'production';

return GeneralConfig::create()
    // Set the default week start day for date pickers (0 = Sunday, 1 = Monday, etc.)
    ->defaultWeekStartDay(1)
    // Prevent generated URLs from including "index.php"
    ->omitScriptNameInUrls()
    // Preload Single entries as Twig variables
    ->preloadSingles()
    // Prevent user enumeration attacks
    ->preventUserEnumeration()
    // Front-end member accounts: email is the login identifier, no separate
    // username field
    ->useEmailAsUsername()
    // Front-end account (templates/_konto). Login, registration and
    // "Passwort vergessen" are entries of the Benutzerkonto section; the
    // other pages are routes in config/routes.php. Paths without a
    // leading slash.
    // Links in the account emails
    ->autoLoginAfterAccountActivation()

    ->devMode($isDev)
    ->disallowRobots(!$isProd)
    ->enableTemplateCaching($isProd)

    // Set the @webroot alias so the clear-caches command knows where to find CP resources
    ->aliases([
        '@webroot' => dirname(__DIR__) . '/web',
        '@assetsRootUrl' => dirname(__DIR__) . '/web/assets',
        '@assetsUrl' => App::env('PRIMARY_SITE_URL').'/assets',
    ])

;
