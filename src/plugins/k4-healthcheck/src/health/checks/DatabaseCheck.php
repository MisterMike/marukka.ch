<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use craft\db\Connection;
use k4\k4HealthCheck\health\Check as BaseCheck;

class DatabaseCheck extends BaseCheck
{
    public function run(): self
    {
        try {
            $db = Craft::$app->getDb();

            $this->meta([
                'driver' => $db->getDriverName(),
            ]);

            $command = $db->createCommand('SELECT COUNT(id) FROM `sites`');

            $siteCount = $command->queryOne();

            $this->shortSummary($siteCount["COUNT(id)"]." sites");

            return $this->ok();


        } catch (Exception $exception) {
            return $this->failed("Could not connect to the database: `{$exception->getMessage()}`");
        }
    }
}
