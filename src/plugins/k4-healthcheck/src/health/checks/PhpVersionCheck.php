<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use k4\k4HealthCheck\health\Check as BaseCheck;

class PhpVersionCheck extends BaseCheck
{
    public function run(): self
    {
        // just in case the CONST is not yet defined..
        if (!defined('PHP_VERSION_ID')) {

            $version = explode('.', PHP_VERSION, 3);

            define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));

            if (PHP_VERSION_ID2 < 50207) {
                define('PHP_MAJOR_VERSION', $version[0]);
                define('PHP_MINOR_VERSION', $version[1]);
                define('PHP_RELEASE_VERSION', $version[2]);
            }
        }

        $this->shortSummary(PHP_VERSION_ID);

        if (PHP_VERSION_ID < 70400) {
            return $this->failed("Has old PHP version.");
        }

        return $this->ok();
    }
}
