<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\k4TwigExtensionsTrait;

class ClassExtension extends AbstractExtension
{
    use k4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'Class Extension');
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            $this->addFunction('getClass'),
        ];
    }

    /**
     * Get the class
     *
     * @param $object
     * @return string
     * @throws \ReflectionException
     * @throws \Twig_Error_Loader
     */
    public function getClass($object):string
    {
        if (! is_object($object)) {
            throw new \Twig_Error_Loader(
                Craft::t('k4twigextensions', 'Value passed to the getClass function must be of type object.')
            );
        }

        return (new \ReflectionClass($object))->getShortName();
    }
}
