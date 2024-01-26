<?php

namespace modules\k4twigextensionsmodule\traits;

trait K4TwigExtensionsTrait
{
    /**
     * @param string $name
     * @return \\Twig\TwigFilter
     */
    private function addFilter(string $name)
    {
        return new \Twig\TwigFilter($name, [$this, $name]);
    }

    /**
     * @param $name
     * @return \Twig\TwigFunction
     */
    private function addFunction($name)
    {
        return new \Twig\TwigFunction($name, [$this, $name]);
    }
}
