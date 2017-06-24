<?php

namespace AppBundle\Serializer\Listener;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class PostListener implements EventSubscriberInterface {

    /**
     * Returns the events to which this class has subscribed.
     *
     * @return array
     */
    public static function getSubscribedEvents() {
        return [
          [
              'event' => Events::POST_SERIALIZE,
              'format' => 'json',
              'class' => 'AppBundle\Entity\Post',
              'method' => 'onPostSerialize'
          ]
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public static function onPostSerialize(ObjectEvent $event) {
        $object = $event->getObject();
        $date = new \DateTime();
        $event->getVisitor()->addData('delivered_at', $date->format('jS \of F Y h:i:s A'));
    }

}