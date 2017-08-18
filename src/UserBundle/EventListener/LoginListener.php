<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 03/08/2017
 * Time: 22:41
 */

namespace UserBundle\EventListener;



use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class LoginListener implements EventSubscriberInterface
{

    /**
     * @var Session
     */
    private $session;



    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure'];
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $this->session->getFlashBag()->add('warning', 'Mauvais identifiant ou mot de passe');
    }
}