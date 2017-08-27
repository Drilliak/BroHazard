<?php

namespace AppBundle\Controller;

use Abraham\TwitterOAuth\TwitterOAuthException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    const TWITTER_ACCOUNT = 'Brohazard_FR';

    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $lastsPosts = $em->getRepository('AppBundle:Post')->findLastPosts(5);

        try {
            $twitter = $this->get('app.twitter.api');
            $lastsTweets = $twitter->lastTweets([self::TWITTER_ACCOUNT], 5);
        } catch (TwitterOAuthException $e) {
            $lastsTweets = 'Impossible de se connecter à Twitter, veuillez réessayer utlérieurement.';
        }

        return $this->render('@App/Home/index.html.twig',
            [
                'lastPosts'  => $lastsPosts,
                'lastTweets' => $lastsTweets
            ]);
    }

    public function googleWebMasterToolsAction(): Response
    {
        return $this->render('@App/Home/google-webmaster-tool.html.twig');
    }
}
