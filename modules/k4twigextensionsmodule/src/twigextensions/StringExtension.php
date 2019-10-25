<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\k4TwigExtensionsTrait;

class StringExtension extends AbstractExtension
{
    use k4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'String Extension');
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            $this->addFilter('truncate'),
        ];
    }

    /**
     * Cut of text if it's to long.
     *
     * @param string $value
     * @param int $length
     * @param string $replace
     * @return string
     */
    public function truncate($value, $length, $replace = '...'):string
    {
        if (! isset($value)) {
            return null;
        }

        return strlen($value) <= $length
            ? $value
            : substr($value, 0, $length - strlen($replace))
                . $replace;
    }
}
