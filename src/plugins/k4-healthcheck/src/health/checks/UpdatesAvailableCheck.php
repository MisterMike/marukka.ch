<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use k4\k4HealthCheck\health\Check as BaseCheck;

class UpdatesAvailableCheck extends BaseCheck
{
    public function run(): self
    {
        $count = Craft::$app->getUpdates()->getTotalAvailableUpdates();
        $critical = Craft::$app->getUpdates()->getIsCriticalUpdateAvailable();
        $migrations = Craft::$app->getUpdates()->getAreMigrationsPending();

        $this->shortSummary($count);

        if ($critical > 0) {
            return $this->failed("{$critical} critical updates available!");
        }

        if ($count > 0) {
            return $this->warning("{$count} updates available.");
        }

        if ($migrations > 0) {
            return $this->failed("Migrations need to run!");
        }

        return $this->ok();
    }
}
