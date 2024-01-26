<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use k4\k4HealthCheck\health\Check as BaseCheck;

class DeprecationWarningsCheck extends BaseCheck
{
    public function run(): self
    {
        $count = Craft::$app->getDeprecator()->getTotalLogs();

        $this->shortSummary($count);

        if ($count > 0) {
            return $this->warning("{$count} deprecation warnings.");
        }

        return $this->ok();
    }
}
