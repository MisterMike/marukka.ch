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

    public function timeAgo($time,$rcs = 0) {
       
       $currentTime = time(); 
       $diffTime = $currentTime-$time;
       
       $periods = array('sec','min','h','d','w','m','y','decade');
       
       $duration = array(1,60,3600,86400,604800,2630880,31570560,315705600);
       
       for($v = sizeof($duration)-1; ($v >= 0)&&(($no = $diffTime/$duration[$v])<=1); $v--); if($v < 0) $v = 0; $_time = $currentTime-($diffTime%$duration[$v]);

       $no = floor($no); if($no <> 1) $periods[$v] .='s'; $x=sprintf("%d %s ",$no,$periods[$v]);
       if(($rcs == 1)&&($v >= 1)&&(($currentTime-$_time) > 0)) $x .= timeAgo($_time);
       return $x;
    }

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
