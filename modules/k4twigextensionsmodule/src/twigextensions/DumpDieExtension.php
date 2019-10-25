<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\k4TwigExtensionsTrait;

class DumpDieExtension extends AbstractExtension
{
    use k4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'Dump and Die Extension');
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            $this->addFunction('d'),
            $this->addFunction('dd')
        ];
    }

    /**
     * Dump
     */
    public function d()
    {
        call_user_func_array('dump', func_get_args());
    }

    /**
     * Dump and die
     */
    public function dd()
    {
        call_user_func_array('dump', func_get_args());
        die();
    }
}
