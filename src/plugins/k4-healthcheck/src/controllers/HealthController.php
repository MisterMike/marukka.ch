<?php

namespace k4\k4HealthCheck\controllers;

use Craft;
use craft\web\Controller;
use k4\k4HealthCheck\Plugin;
use k4\k4HealthCheck\health\checks\DatabaseCheck;
use k4\k4HealthCheck\health\checks\CmsCheck;
use k4\k4HealthCheck\health\checks\DebugModeCheck;
use k4\k4HealthCheck\health\checks\DeprecationWarningsCheck;
use k4\k4HealthCheck\health\checks\EnvironmentCheck;
use k4\k4HealthCheck\health\checks\QueueFailedCheck;
use k4\k4HealthCheck\health\checks\RequirementsCheck;
use k4\k4HealthCheck\health\checks\UpdatesAvailableCheck;
use k4\k4HealthCheck\health\checks\MigrationRequiredCheck;
use k4\k4HealthCheck\health\checks\PhpVersionCheck;
use k4\k4HealthCheck\health\checks\UsedDiskSpaceCheck;
use OhDear\HealthCheckResults\CheckResults;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class HealthController extends BaseController
{
    protected int|bool|array $allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

    protected array $checks = [
        DatabaseCheck::class,
        CmsCheck::class,
        DebugModeCheck::class,
        DeprecationWarningsCheck::class,
        EnvironmentCheck::class,
        QueueFailedCheck::class,
        RequirementsCheck::class,
        UpdatesAvailableCheck::class,
        MigrationRequiredCheck::class,
        UsedDiskSpaceCheck::class,
        PhpVersionCheck::class
    ];

    /*
     * URL : /actions/k4-healthcheck/health/check-results
    */

    public function actionCheckResults(): Response
    {
        $this->requireHealthCheckSecret();

        $checkResults = $this->getCheckResults();

        $responseData = json_decode($checkResults->toJson());

        $this->response->setNoCacheHeaders();

        return $this->asJson($responseData);
    }

    private function getCheckResults(): CheckResults
    {
        $checkResults = new CheckResults();

        foreach ($this->checks as $checkClass) {
            $check = new $checkClass();

            $checkResults->addCheckResult($check->run());
        }

        return $checkResults;
    }
}
