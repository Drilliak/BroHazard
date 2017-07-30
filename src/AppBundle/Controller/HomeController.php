<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{


    /**
     * @return Response
     */
    public function indexAction(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $lastsPosts = $em->getRepository('AppBundle:Post')->findLastPosts(3);

        $twitter = $this->get('twitter.api');

        $lastsTweets = $twitter->lastTweets(["Rolesafe", "Drilliak", "Nekaator"], 5);

        return $this->render('@App/Home/index.html.twig',
            [
                "lastPosts"  => $lastsPosts,
                "lastTweets" => $lastsTweets
            ]);
    }


}