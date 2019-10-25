<?php
/**
 * k4PDFExtractor module for Craft CMS 3.x
 *
 * Extracts Content from PDF Files
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 kreisvier communications ag
 */

namespace modules\k4pdfextractormodule\twigextensions;

use modules\k4pdfextractormodule\K4PDFExtractorModule;

use Craft;
use Spatie\PdfToText\Pdf;
use Symfony\Component\Process\Process;
use voku\helper\UTF8;

/**
 * @author    kreisvier communications ag
 * @package   K4PDFExtractorModule
 * @since     1
 */
class K4PDFExtractorModuleTwigExtension extends \Twig_Extension
{
    const PDFTOTEXT_BINARY = '/usr/bin/pdftotext';


    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'K4PDFExtractorModule';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('getSearchContentPDF', [$this, 'getSearchContentPDF']),
            new \Twig_SimpleFilter('getPDFContent', [$this, 'getPDFContent']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('someFunction', [$this, 'someInternalFunction']),
        ];
    }
    
    /**
     * @param null $file
     *
     * @return string
     */
    public function getSearchContentPDF($file)
    {
        // Set memory limit
        ini_set('memory_limit', -1);
        // Set time limit
        set_time_limit(0);

        ini_set('max_execution_time', 300);

        if ($this->shellCommandCheck())
        {
            $raw = (new \Spatie\PdfToText\Pdf(self::PDFTOTEXT_BINARY))
                ->setPdf($file)
                ->setOptions(['enc UTF-8'])
                ->addOptions(['layout'])
                ->text();

            $raw = preg_replace('/\PL/u', ' ', $raw);

            $string = implode(' ', array_unique(explode(' ', strtolower($raw))));

            $string = preg_replace('!\s+!', ' ', $string);
            $string = trim(preg_replace('/\s\s+/', ' ', $string));

            return UTF8::fix_utf8($string);
        } else {
            return 'abc';
        }
    }
    
    public function getPDFContent($file)
    {
      // Parse pdf file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($file);
        $raw = "";
        // Retrieve all pages from the pdf file.
        $pages  = $pdf->getPages();

        // Loop over each page to extract text.
        foreach ($pages as $page) {
            $raw = $raw . $page->getText();
        }
        
        return utf8_encode($raw);
    }

    private function shellCommandCheck()
    {
        $process = new Process([self::PDFTOTEXT_BINARY,'-v']);
        $process->run();

        if (!$process->isSuccessful()) {
            return false;
        }
        return true;

    }
}
