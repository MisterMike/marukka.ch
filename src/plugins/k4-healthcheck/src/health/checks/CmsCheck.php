<?php

namespace k4\k4HealthCheck\health\checks;

use Craft;
use craft\helpers\App;
use k4\k4HealthCheck\health\Check as BaseCheck;

class CmsCheck extends BaseCheck
{
    public function run(): self
    {
        $versionString = Craft::$app->getVersion();
        $edition = App::editionName(Craft::$app->getEdition());

        $version = explode(".",Craft::$app->getVersion());

        $this->shortSummary($versionString.','.$edition);

        $this->meta([
            'major' => intval($version[0]),
            'minor' => intval($version[1]),
            'patch' => $version[2] ? intval($version[2]):0,
        ]);

        //return $this->warning("Craft {$edition} {$versionString}.");

        $intVersion = floatval($version);

        if ($version[0] == 3 && $version[1] < 5) {
            return $this->failed("This Craft CMS Version needs updates");
        }

        if ($version[0] == 2 ) {
            return $this->failed("This Craft CMS Version is outdated");
        }

        return $this->ok();
    }
}

