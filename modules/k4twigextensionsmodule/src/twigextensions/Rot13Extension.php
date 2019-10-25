<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\k4TwigExtensionsTrait;

class Rot13Extension extends AbstractExtension
{
    use k4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'ROT13 Mail Address Scrambler');
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            $this->addFilter('rot13'),
        ];
    }

    /**
     * @param string $string
     * @return string
     */
    public function rot13(string $string):string
    {
        return str_rot13($string);
    }
}
