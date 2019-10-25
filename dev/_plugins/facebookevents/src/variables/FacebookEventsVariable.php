<?php
/**
 * FacebookEvents plugin for Craft CMS 3.x
 *
 * A Simple Plugin to load Facebook Events using Graph SDK
 *
 * @link      parhelia.ch
 * @copyright Copyright (c) 2019 Mischa Sprecher
 */

namespace nibiru\facebookevents\variables;

use nibiru\facebookevents\FacebookEvents;

use Craft;

/**
 * FacebookEvents Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.facebookEvents }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Mischa Sprecher
 * @package   FacebookEvents
 * @since     0.1.0
 */
class FacebookEventsVariable
{

    /**
     *
     * @param null $optional
     * @return string
     */

    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }

    public function mischaVar($optional = null)
    {

        return FacebookEvents::$plugin->facebookEventsService->exampleService();
    }

    public function displayEvents()
    {
        //return craft()->facebookEvents->getEvents();
        return FacebookEvents::$plugin->facebookEventsService->getEvents();
    }

    public function mischa()
    {
        return FacebookEvents::$plugin->FacebookEventsService->getMischa();
    }
}
