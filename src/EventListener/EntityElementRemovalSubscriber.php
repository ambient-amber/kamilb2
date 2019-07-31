<?php

namespace App\EventListener;

use App\Service\UploadHelper;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class EntityElementRemovalSubscriber implements EventSubscriber
{
    private $uploadHelper;

    public function __construct(UploadHelper $uploadHelper)
    {
        $this->uploadHelper = $uploadHelper;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postRemove
        ];
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // Удаление сгенерированного изображения элемента
        if (method_exists($entity, 'getImageHash')) {
            $imageHashName = $entity->getImageHash();

            $this->uploadHelper->unloadHashFile($imageHashName);
        }
    }
}