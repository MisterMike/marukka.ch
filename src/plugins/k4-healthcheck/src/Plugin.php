<?php

namespace k4\k4HealthCheck;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\base\Model;
use craft\events\RegisterUrlRulesEvent;
use craft\web\Application;
use craft\web\UrlManager;
use k4\k4HealthCheck\models\Settings;
use yii\base\Event;

class Plugin extends BasePlugin
{
    public bool $hasCpSettings = true;

    public function init()
    {
        Event::on(Application::class, Application::EVENT_INIT, function() {
            parent::init();

            if (Craft::$app->getRequest()->getIsConsoleRequest()) {
                return;
            }

            $this->_registerSiteRoutes();
        });

    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'k4-healthcheck/settings',
            [ 'settings' => $this->getSettings() ]
        );
    }

    private function _registerSiteRoutes()
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules['k4-health-check-results'] = 'k4-healthcheck/health/check-results';
        });
    }
}
