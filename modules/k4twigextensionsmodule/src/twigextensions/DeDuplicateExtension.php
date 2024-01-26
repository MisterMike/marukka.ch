<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\K4TwigExtensionsTrait;

class DeDuplicateExtension extends AbstractExtension
{
    use K4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'DeDuplicate Extension');
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            $this->addFilter('deduplicate')
        ];
    }

    /**
     * @return array|\Twig_Filter[]
     */

    public function deduplicate( $inArray=Array() ) {
        $outArray = ( $inArray );
        $outArray = array_unique( $outArray );
        $outArray = array_values( $outArray );
    return $outArray;
}
}
