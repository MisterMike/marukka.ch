<?php

namespace k4\k4HealthCheck\controllers;

use Craft;
use craft\web\Controller;
use k4\k4HealthCheck\Plugin;
use yii\web\ForbiddenHttpException;

abstract class BaseController extends Controller
{
    public function requireHealthCheckSecret()
    {
        $headers = Craft::$app->request->headers;
        $healthCheckSecret = Plugin::getInstance()->getSettings()->getHealthCheckSecret();

        if (empty($healthCheckSecret)) {
            throw new ForbiddenHttpException('k4 Health Check Secret not configured.');
        }

//        if ($headers->get('k4-health-check-secret') !== $healthCheckSecret) {
//            throw new ForbiddenHttpException('User is not permitted to perform this action.');
//        }
    }
}
