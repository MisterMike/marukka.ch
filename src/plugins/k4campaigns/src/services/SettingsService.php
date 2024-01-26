<?php

namespace k4\k4campaigns\services;

use craft\base\Volume;
use craft\helpers\App;

use Craft;
use craft\helpers\Component;
use k4\k4campaigns\K4Campaigns;
use k4\k4campaigns\models\SettingsModel;
use Kint\Kint;


class SettingsService extends Component
{
    // Public Methods
    // =========================================================================
	
    /**
     * Saves plugin settings
     *
     * @param SettingsModel $settings The plugin settings
     *
     * @return bool Whether the settings were saved successfully
     */
    public function saveSettings(SettingsModel $settings): bool
    {
        if (!$settings->validate()) {
            return false;
        }
        return Craft::$app->plugins->savePluginSettings(K4Campaigns::$plugin, $settings->getAttributes());
    }
}
