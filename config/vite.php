<?php

use craft\helpers\App;


// Decides which port to use for devServerPublic. This allows
// you to visit the http or https port. If you plant to only
// use http, make sure that PRIMARY_SITE_URL is set to http
// 
// Match ports to .ddev/config.yaml -> web_extra_exposed_ports
$host = Craft::$app->getRequest()->getIsConsoleRequest()
    ? App::env('PRIMARY_SITE_URL')
    : Craft::$app->getRequest()->getHostInfo();
$httpPort = 3005;
$httpsPort = 3006; 
$devServerPort = str_starts_with($host, 'https') ? $httpsPort : $httpPort;

return [
    'devServerPublic' => "$host:$devServerPort",
    'serverPublic' => '/dist/',
    'useDevServer' => (App::env('CRAFT_ENVIRONMENT') ?? App::env('ENVIRONMENT')) === 'dev',
    'manifestPath' => '@webroot/dist/.vite/manifest.json',
    'checkDevServer' => true,
    'devServerInternal' => 'http://localhost:3000',
];
