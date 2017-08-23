<?php

namespace AppBundle;

final class AppEvents
{
    /**
     * @Event("AppBundle\Event\NewPostEvent")
     */
    const NEW_POST = "app.new_post";
}