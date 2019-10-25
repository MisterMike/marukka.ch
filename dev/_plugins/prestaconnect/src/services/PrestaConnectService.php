<?php
/**
 * PrestaConnect plugin for Craft CMS 3.x
 *
 * Plugin to connect to a Prestashop Webservice and retrieve product data
 *
 * @link      marukka.ch
 * @copyright Copyright (c) 2019 Mischa Sprecher
 */

namespace nibiru\prestaconnect\services;

use nibiru\prestaconnect\PrestaConnect;

use Craft;
use craft\base\Component;

/**
 * PrestaConnectService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Mischa Sprecher
 * @package   PrestaConnect
 * @since     1.0.0
 */
class PrestaConnectService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     PrestaConnect::$plugin->prestaConnectService->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (PrestaConnect::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }
}
