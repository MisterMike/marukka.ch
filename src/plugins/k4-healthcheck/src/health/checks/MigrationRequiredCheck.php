<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use k4\k4HealthCheck\health\Check as BaseCheck;

class MigrationRequiredCheck extends BaseCheck
{
    public function run(): self
    {
        $count = Craft::$app->getUpdates()->getIsCraftUpdatePending();

        $this->shortSummary($count);

        if ($count > 0) {
            return $this->warning("Database migration required.");
        }

        return $this->ok();
    }
}
