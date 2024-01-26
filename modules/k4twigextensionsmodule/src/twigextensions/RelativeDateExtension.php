<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\K4TwigExtensionsTrait;

class RelativeDateExtension extends AbstractExtension
{
    use K4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4-twigextensions-module', 'Relative Time Extension');
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
     * @usage: {{ itemDate|relativeDate("now", 1,'',1 ) }}
     */
    public function relativeDate($from, $to = false, $precision = 1, $suffix = 'ago', $weeks = false): string
    {
        if (! $to || $to == "now") {
            $toDate = new \DateTime(gmdate('Y-m-d H:i:s'));
        }

        $fromDate = \DateTime::createFromFormat('Y-m-d H:i:s', $from);

        //$fromDate = $fromDate->format('Y-m-d h:i:s');

        if ( $weeks ) {

                if($from > $to) return datediffInWeeks($to, $from);
                //$first = \DateTime::createFromFormat('Y-m-d H:i:s', $from);
                //$second = \DateTime::createFromFormat('Y-m-d H:i:s', $to);
                return floor($fromDate->diff($toDate)->days/7)."w";

        } else {
            $diff = $fromDate->diff($toDate);

            $parts = [];

//        $map = [
//            'y' => Craft::t('k4-twigextensions-module', 'year'),
//            'm' => Craft::t('k4-twigextensions-module', 'month'),
//            'd' => Craft::t('k4-twigextensions-module', 'day'),
//            'h' => Craft::t('k4-twigextensions-module', 'hour'),
//            'i' => Craft::t('k4-twigextensions-module', 'minute'),
//            's' => Craft::t('k4-twigextensions-module', 'second'),
//        ];

            $map = [
                'y' => 'year',
                'm' => 'month',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            ];

            if ($suffix) {
                //$suffix = Craft::t('k4-twigextensions-module', $suffix);
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

}
