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

        $lastsTweets = $twitter->lastTweets(["Rolesafe"], 5);

        return $this->render('@App/Home/index.html.twig',
            [
                "lastPosts"  => $lastsPosts,
                "lastTweets" => $lastsTweets
            ]);
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function showAction(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);

        if (is_null($post)) {
            throw $this->createNotFoundException("La page n'existe pas.");
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $comment->setCreationDate(new \DateTime());
            $username = $this->get('security.token_storage')->getToken()->getUsername();
            $comment->setUsername($username);
            $comment->setPost($post);
            if ($form->isValid()){
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute("post_show", ["id" => $post->getId()]);
            }
        }
        return $this->render("AppBundle:Home:post.html.twig", [
            "post" => $post,
            "comment_form" => $form->createView(),
        ]);
    }
}