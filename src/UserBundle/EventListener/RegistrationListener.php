<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 02/08/2017
 * Time: 23:57
 */

namespace UserBundle\EventListener;


use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationListener implements EventSubscriberInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var Session
     */
    private $session;

    public function __construct(UrlGeneratorInterface $router, Session $session){

        $this->router = $router;
        $this->session = $session;
    }
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [FOSUserEvents::REGISTRATION_CONFIRM => "onRegistrationConfirm"];
    }

    public function onRegistrationConfirm(GetResponseUserEvent $event)
    {
        $url = $this->router->generate('homepage');
        $this->session->getFlashBag()->set('success', 'Votre inscription a bien été validée.');
        $event->setResponse(new RedirectResponse($url));
    }
}