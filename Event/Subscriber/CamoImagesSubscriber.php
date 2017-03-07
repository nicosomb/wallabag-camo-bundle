<?php

namespace Nicosomb\WallabagCamoBundle\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Nicosomb\WallabagCamoBundle\Helper\CamoUpdateImages;
use Wallabag\CoreBundle\Entity\Entry;
use Wallabag\CoreBundle\Event\EntrySavedEvent;
use Doctrine\ORM\EntityManager;

class CamoImagesSubscriber implements EventSubscriberInterface
{
    private $em;
    private $camoDownloadImages;
    private $enabled;
    private $logger;

    public function __construct(EntityManager $em, CamoUpdateImages $camoDownloadImages, $enabled, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->camoDownloadImages = $camoDownloadImages;
        $this->enabled = $enabled;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            EntrySavedEvent::NAME => 'onEntrySaved',
        ];
    }

    /**
     * Download images and updated the data into the entry.
     *
     * @param EntrySavedEvent $event
     */
    public function onEntrySaved(EntrySavedEvent $event)
    {
        if (!$this->enabled) {
            $this->logger->debug('CamoImagesSubscriber: disabled.');

            return;
        }

        $entry = $event->getEntry();

        $html = $this->downloadImages($entry);
        if (false !== $html) {
            $this->logger->debug('CamoImagesSubscriber: updated html.');

            $entry->setContent($html);
        }

        // update preview picture
        $previewPicture = $this->downloadPreviewImage($entry);
        if (false !== $previewPicture) {
            $this->logger->debug('CamoImagesSubscriber: update preview picture.');

            $entry->setPreviewPicture($previewPicture);
        }

        $this->em->persist($entry);
        $this->em->flush();
    }

    /**
     * Download all images from the html.
     *
     * @todo If we want to add async download, it should be done in that method
     *
     * @param Entry $entry
     *
     * @return string|false False in case of async
     */
    private function downloadImages(Entry $entry)
    {
        return $this->camoDownloadImages->processHtml(
            $entry->getContent()
        );
    }

    /**
     * Download the preview picture.
     *
     * @todo If we want to add async download, it should be done in that method
     *
     * @param Entry $entry
     *
     * @return string|false False in case of async
     */
    private function downloadPreviewImage(Entry $entry)
    {
        return $this->camoDownloadImages->processSingleImage(
            $entry->getPreviewPicture()
        );
    }
}
