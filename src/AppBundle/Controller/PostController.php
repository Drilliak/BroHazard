<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{

    /**
     *
     * Page relative à l'article
     *
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

        $isAuthenticated = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, ["isAuthenticated" => $isAuthenticated]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $isAuthenticated) {
            $comment->setCreationDate(new \DateTime());
            $username = $this->get('security.token_storage')->getToken()->getUsername();
            $comment->setUsername($username);
            $comment->setPost($post);
            if ($form->isValid()) {
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute("post_show", ["id" => $post->getId()]);
            }
        }
        return $this->render("AppBundle:Post:post.html.twig", [
            "post"         => $post,
            "comment_form" => $form->createView(),
        ]);
    }

    /**
     * Page listant les articles faisant référence à la catégorie
     *
     * @return Response
     */
    public function categoryAction(Request $request, $categorySlug): Response
    {
        $em = $this->getDoctrine();
        $query = $em->getRepository('AppBundle:Post')->findByCategoryQuery($categorySlug);
        $category = $em->getRepository("AppBundle:Category")->findOneBy(["slug" => $categorySlug]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render("@App/Post/category.html.twig", [
            "pagination" => $pagination,
            "category"   => $category
        ]);

    }

    /**
     * Liste tous les articles du site.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listPostsAction(Request $request): Response
    {
        $em = $this->getDoctrine();
        $query = $em->getRepository("AppBundle:Post")
            ->findAllQuery();

        $paginator = $this->get("knp_paginator");
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt("page", 1)
        );

        return $this->render("AppBundle:Post:posts.html.twig", [
            "pagination" => $pagination
        ]);
    }

    /**
     * Liste tous les articles correspondant à un utilisateur
     *
     * @param Request $request
     * @param         $idAuthor
     *
     * @return Response
     */
    public function authorAction(Request $request, $idAuthor): Response
    {
        $em = $this->getDoctrine();
        $query = $em->getRepository("AppBundle:Post")
            ->findByAuthorQuery($idAuthor);

        $author = $em->getRepository("UserBundle:User")->findOneBy(["id" => $idAuthor]);

        if (is_null($author)) {
            throw $this->createNotFoundException("La page n'existe pas.");
        }

        $paginator = $this->get("knp_paginator");
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt("page", 1),
            3
        );

        return $this->render("AppBundle:Post:author.html.twig", [
            "pagination" => $pagination,
            "author"     => $author
        ]);
    }
}
