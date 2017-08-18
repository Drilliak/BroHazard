<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 01/08/2017
 * Time: 21:25
 */

namespace UserBundle\EventListener;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Surcharge de la classe éponyme du bundle FosUserBundle afin d'ajuster le comportement
 * après inscription
 * Class EmailConfirmationListener
 * @package UserBundle\EventListener
 */
class EmailConfirmationListener implements EventSubscriberInterface
{

    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $router, SessionInterface $session)
    {

        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [FOSUserEvents::REGISTRATION_SUCCESS => "onRegistrationSuccess"];
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();

        $user->setEnabled(false);
        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $this->mailer->sendConfirmationEmailMessage($user);

        $url = $this->router->generate('homepage');
        $event->setResponse(new RedirectResponse($url));
    }
}