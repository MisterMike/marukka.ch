<?php
/**
 * FacebookEvents plugin for Craft CMS 3.x
 *
 * A Simple Plugin to load Facebook Events using Graph SDK
 *
 * @link      parhelia.ch
 * @copyright Copyright (c) 2019 Mischa Sprecher
 */

namespace nibiru\facebookevents\services;


use nibiru\facebookevents\FacebookEvents;

use Craft;
use craft\base\Component;

use Facebook\Facebook;

/**
 * FacebookEventsService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Mischa Sprecher
 * @package   FacebookEvents
 * @since     0.1.0
 */
class FacebookEventsService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     FacebookEvents::$plugin->facebookEventsService->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (FacebookEvents::$plugin->getSettings()->facebookPage) {
        }

        return $result;
    }

    public function getEvents(){

        // Get plugin settings
        //$settings = craft()->plugins->getPlugin('facebookEvents')->getSettings();
        $settings = FacebookEvents::$plugin->getSettings();

        if (!$settings->facebookAppId) {
          return;
        }
        
        define("APPID", $settings->facebookAppId);
        define("SECRET", $settings->facebookSecret);
        define("PAGE", $settings->facebookPage);


        if ($settings->facebookFilter) {
            define("FILTER", 'past');
        } else {
            define("FILTER", 'upcoming');
        }
        
        // Get events list from Facebook API
        $fb = new Facebook([
          'app_id' => APPID,
          'app_secret' => SECRET,
          'default_graph_version' => 'v4.0',
          'default_access_token' => $settings['facebookAccessToken']
        ]);

        $response = $fb->get('/'.PAGE.'/events?time_filter=past')->getDecodedBody();
        return $response;

    }

    public function getMischa(){
            $mySettings = FacebookEvents::$plugin->getSettings();

            return $mySettings;

    }


}
