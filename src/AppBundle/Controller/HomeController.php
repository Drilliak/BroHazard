<?php

namespace AppBundle\Controller;


use Abraham\TwitterOAuth\TwitterOAuthException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{


    /**
     * @return Response
     */
    public function indexAction(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $lastsPosts = $em->getRepository('AppBundle:Post')->findLastPosts(5);

        $em =$this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:TwitterAccount");
        $queryResults = $repository->findAllName();
        $accounts = [];
        foreach ($queryResults as $queryResult){
            $accounts[] = $queryResult['username'];
        }
        try {
            $twitter = $this->get('twitter.api');
            $lastsTweets = $twitter->lastTweets($accounts, 5);
        } catch (TwitterOAuthException $e) {
            $lastsTweets = 'Impossible de se connecter à Twitter, veuillez réessayer utlérieurement.';
        }


        return $this->render('@App/Home/index.html.twig',
            [
                "lastPosts"  => $lastsPosts,
                "lastTweets" => $lastsTweets
            ]);
    }


}