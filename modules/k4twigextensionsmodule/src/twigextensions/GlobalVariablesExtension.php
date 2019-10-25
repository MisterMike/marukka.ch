<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalVariablesExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @return array
     */
    public function getGlobals():array
    {
        $settings = Craft::$app->config->getConfigFromFile('k4twigextensions');

        return $settings['globals'] ?? [];
    }
}
