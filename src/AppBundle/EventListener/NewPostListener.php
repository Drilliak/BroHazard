<?php

namespace AppBundle\EventListener;

use AppBundle\AppEvents;
use AppBundle\Twitter\Twitter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewPostListener implements EventSubscriberInterface
{
    

    public static function getSubscribedEvents()
    {
        return [AppEvents::NEW_POST => "onNewPost"];
    }

    public function onNewPost(){

    }
}

