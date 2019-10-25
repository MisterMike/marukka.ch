<?php
/**
 * FacebookEvents plugin for Craft CMS 3.x
 *
 * A Simple Plugin to load Facebook Events using Graph SDK
 *
 * @link      parhelia.ch
 * @copyright Copyright (c) 2019 Mischa Sprecher
 */

namespace nibiru\facebookevents;

use nibiru\facebookevents\services\FacebookEventsService as FacebookEventsServiceService;
use nibiru\facebookevents\variables\FacebookEventsVariable;
use nibiru\facebookevents\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Mischa Sprecher
 * @package   FacebookEvents
 * @since     0.1.0
 *
 * @property  FacebookEventsServiceService $facebookEventsService
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class FacebookEvents extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * FacebookEvents::$plugin
     *
     * @var FacebookEvents
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '0.1.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * FacebookEvents::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register services as components
        $this->setComponents([
            'facebookEvents' => FacebookEventsVariable::class,
        ]);

        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('facebookEvents', FacebookEventsVariable::class);
            }
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'facebook-events/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
