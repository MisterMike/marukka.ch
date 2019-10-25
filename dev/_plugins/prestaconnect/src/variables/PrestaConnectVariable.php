<?php
/**
 * PrestaConnect plugin for Craft CMS 3.x
 *
 * Plugin to connect to a Prestashop Webservice and retrieve product data
 *
 * @link      marukka.ch
 * @copyright Copyright (c) 2019 Mischa Sprecher
 */

namespace nibiru\prestaconnect\variables;

use nibiru\prestaconnect\PrestaConnect;

use Craft;

/**
 * PrestaConnect Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.prestaConnect }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Mischa Sprecher
 * @package   PrestaConnect
 * @since     1.0.0
 */
class PrestaConnectVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.prestaConnect.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.prestaConnect.exampleVariable(twigValue) }}
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
}
