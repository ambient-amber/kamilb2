<?php

namespace App\EventListener\Form;

use App\Service\UploadHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateImageSubscriber implements EventSubscriberInterface
{
    /*private $uploadHelper;

    public function __construct(UploadHelper $uploadHelper)
    {
        $this->uploadHelper = $uploadHelper;
    }*/

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData'
        ];
    }

    public function postSetData(FormEvent $event)
    {
        $entity = $event->getData();
        $form = $event->getForm();

        echo '<pre>';
        print_r($entity->getImageHash());
        echo '</pre>';

        echo '<pre>';
        print_r($form->get('url'));
        echo '</pre>';

        exit;

        /*// Удаление сгенерированного изображения элемента
        if (method_exists($entity, 'getImageHash')) {
            $imageHashName = $entity->getImageHash();

            $this->uploadHelper->unloadHashFile($imageHashName);
        }*/
    }
}