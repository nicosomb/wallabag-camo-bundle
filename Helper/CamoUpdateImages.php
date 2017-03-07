<?php

namespace Nicosomb\WallabagCamoBundle\Helper;

use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use WillWashburn\Phpamo\Phpamo;

class CamoUpdateImages
{
    private $phpamo;
    private $logger;

    public function __construct(Phpamo $phpamo, LoggerInterface $logger)
    {
        $this->phpamo = $phpamo;
        $this->logger = $logger;
    }

    /**
     * Process the html and extract image from it, save them to local and return the updated html.
     *
     * @param string $html
     *
     * @return string
     */
    public function processHtml($html)
    {
        $crawler = new Crawler($html);
        $result = $crawler
            ->filterXpath('//img')
            ->extract(array('src'));

        // download and save the image to the folder
        foreach ($result as $image) {
            $imagePath = $this->processSingleImage($image);

            if (false === $imagePath) {
                continue;
            }

            $html = str_replace($image, $imagePath, $html);
        }

        return $html;
    }

    /**
     * Process a single image:
     *     - retrieve it
     *     - re-saved it (for security reason)
     *     - return the new local path.
     *
     * @param string $imagePath Path to the image to retrieve
     *
     * @return string Relative url to access the image from the web
     */
    public function processSingleImage($imagePath)
    {
        $this->logger->debug('CamoDownloadImages: working on image: '.$imagePath);

        if (null === $imagePath) {
            return false;
        }

        return $this->phpamo->camoHttpOnly($imagePath);
    }
}
