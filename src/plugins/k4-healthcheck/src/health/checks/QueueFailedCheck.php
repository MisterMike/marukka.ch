<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use k4\k4HealthCheck\health\Check as BaseCheck;

class QueueFailedCheck extends BaseCheck
{
    public function run(): self
    {
        $failedJobs = Craft::$app->get('queue')->getTotalFailed();

        $this->shortSummary($failedJobs);

        if ($failedJobs > 0) {
            return $this->failed("Has {$failed} failed jobs.");
        }

        return $this->ok();
    }
}
