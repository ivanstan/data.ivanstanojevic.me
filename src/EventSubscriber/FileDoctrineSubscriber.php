<?php

namespace App\EventSubscriber;

use App\Entity\File;
use App\Service\FileManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

class FileDoctrineSubscriber implements EventSubscriber
{
    private $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof File) {
                $this->fileManager->remove($this->fileManager->getAbsolutePath($entity));
            }
        }
    }
}
