<?php
/**
 * Custom Configuration
 *
 * You can now set your own config settings—as opposed to those Craft supports—from config/custom.php.
 * Any of your custom config settings will be accessible via Craft::$app->config->{mycustomsetting}.
 *
 * @see https://craftcms.com/docs/4.x/upgrade.html#configuration
 */

use craft\helpers\App;

$rev = "20240223v4";

$isDev = App::env('ENVIRONMENT') === 'dev';
$isProd = App::env('ENVIRONMENT') === 'production';

if($isDev) {
    $assetRev = time();
} else {
    $assetRev = $rev;
}


return [
    'craftEnv' => App::env('ENVIRONMENT'),
    'litespeedCacheTag' => craft\helpers\App::env('LITESPEED_CACHE_TAG'),
    'staticAssetsVersion' => $assetRev,
    'debugFrontend' => App::env('DEBUG_FRONTEND'),
    'googleMapApiKey' => App::env('GOOGLE_MAPS_API_KEY')
];

