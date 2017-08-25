<?php

namespace AppBundle\EventListener;

use AppBundle\AppEvents;
use AppBundle\Entity\Tweet;
use AppBundle\Event\NewPostEvent;
use AppBundle\Twitter\Twitter;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class NewPostListener implements EventSubscriberInterface
{
    /**
     * @var Twitter
     */
    private $twitter;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(Twitter $twitter, RouterInterface $router, EntityManager $entityManager)
    {
        $this->twitter = $twitter;
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvents::NEW_POST => "sendTweet",
        ];
    }

    public function sendTweet(NewPostEvent $newPostEvent)
    {
        $post = $newPostEvent->getPost();
        $author = $post->getAuthor()->getUsername();
        $slug = $post->getSlug();
        $categorySlug = $post->getCategory()->getSlug();
        $url = $this->router->generate("post_show", ['slug' => $slug, 'categorySlug' => $categorySlug], UrlGeneratorInterface::ABSOLUTE_URL);
        $homePageUrl = $this->router->generate("homepage", [],UrlGeneratorInterface::ABSOLUTE_URL);
        $tweetApi = $this->twitter->tweet("Nouvel article incroyable de $author disponible ici : $url.\n $homePageUrl");

        $tweet = new Tweet();
        $date = new \DateTime($tweetApi->created_at);
        $date->format("D M d H:i:s +B Y");
        $tweet->setCreatedAt($date);
        $tweet->setIdTwitter($tweetApi->id_str);
        $tweet->setText($tweetApi->text);
        $tweet->setTruncated($tweetApi->truncated);

        $this->entityManager->persist($tweet);
        $this->entityManager->flush();
    }


}

