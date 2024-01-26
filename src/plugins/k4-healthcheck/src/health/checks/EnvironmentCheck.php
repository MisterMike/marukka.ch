<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use k4\k4HealthCheck\health\Check as BaseCheck;

class EnvironmentCheck extends BaseCheck
{
    protected string $expectedEnvironment = 'production';

    public function expectEnvironment(string $expectedEnvironment): self
    {
        $this->expectedEnvironment = $expectedEnvironment;

        return $this;
    }

    public function run(): self
    {
        $actualEnvironment = Craft::$app->getConfig()->env;

        $this->meta([
                 'actual' => $actualEnvironment,
                 'expected' => $this->expectedEnvironment,
             ])
             ->shortSummary($actualEnvironment);

        if ($this->expectedEnvironment !== $actualEnvironment) {
            return $this->failed("The environment was expected to be `{$this->expectedEnvironment}`, but actually was `{$actualEnvironment}`");
        }

        return $this->ok();
    }
}
