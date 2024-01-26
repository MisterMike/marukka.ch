<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use k4\k4HealthCheck\health\Check as BaseCheck;

class DebugModeCheck extends BaseCheck
{
    protected bool $expected = false;

    public function expectedToBe(bool $bool): self
    {
        $this->expected = $bool;

        return $this;
    }

    public function run(): self
    {
        $actual = Craft::$app->getConfig()->general->devMode;

        $this->meta([
                'actual' => $actual,
                'expected' => $this->expected,
             ])
             ->shortSummary($this->convertToWord($actual));

        if ($this->expected !== $actual) {
            return $this->failed("The debug mode was expected to be `{$this->convertToWord((bool)$this->expected)}`, but actually was `{$this->convertToWord((bool)$actual)}`");
        }

        return $this->ok();
    }

    protected function convertToWord(bool $boolean): string
    {
        return $boolean ? 'true' : 'false';
    }
}
