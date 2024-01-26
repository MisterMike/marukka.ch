<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\K4TwigExtensionsTrait;

class SlugExtension extends AbstractExtension
{
    use K4TwigExtensionsTrait;

    private $slugger;

    public function __construct(SluggerInterface $slugger = null)
    {
        $this->slugger = $slugger ?: new AsciiSlugger();
    }

    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'Slug Extension');
    }

    public function getFilters()
    {
        return [
            $this->addFilter('slug'),
        ];
    }

    /**
     * create sllug from string
     *
     * @param string $value
     * @param int $length
     * @param string $replace
     * @return string
     */

    public function slug(string $string, string $separator = '-', ?string $locale = null): AbstractUnicodeString
    {
        return $this->slugger->slug($string, $separator, $locale);
    }
}
