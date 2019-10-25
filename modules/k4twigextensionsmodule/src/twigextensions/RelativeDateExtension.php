<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\k4TwigExtensionsTrait;

class RelativeDateExtension extends AbstractExtension
{
    use k4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'Relative Time Extension');
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            $this->addFilter('relativeDate')
        ];
    }

    /**
     * @param $date
     * @return string
     */
    public function relativeDate($from, $to = false, $precision = 1, $suffix = 'ago'): string
    {
        if (! $to) {
            $to = new \DateTime(gmdate('Y-m-d H:i:s'));
        }

        $diff = (new \DateTime($from->format('Y-m-d H:i:s')))->diff($to);
        $parts = [];

        $map = [
            'y' => Craft::t('k4twigextensionsmodule', 'year'),
            'm' => Craft::t('k4twigextensionsmodule', 'month'),
            'd' => Craft::t('k4twigextensionsmodule', 'day'),
            'h' => Craft::t('k4twigextensionsmodule', 'hour'),
            'i' => Craft::t('k4twigextensionsmodule', 'minute'),
            's' => Craft::t('k4twigextensionsmodule', 'second'),
        ];

        if ($suffix) {
            $suffix = Craft::t('k4twigextensionsmodule', $suffix);
        }

        foreach ($diff as $key => $value) {
            if (isset($map[$key]) && $value) {
                $parts[] = $value . ' ' . $map[$key] . ($value > 1 ? 's' : '');
            }
        }

        $precision = count($parts) < $precision ? 0 : count($parts) - $precision;

        $reversed = array_reverse($parts);

        return implode(
            ', ',
            array_reverse(array_slice($reversed, $precision))
        ) . ($suffix ? ' ' . $suffix : '');
    }
}
