<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\CommentType;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

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
    public function showAction(Request $request, string $slug): Response
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(["slug" => $slug]);

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
                return $this->redirectToRoute("post_show", ["slug" => $post->getSlug()]);
            }
        }

        /** @var bool $hasRight Autorisation de modifier ou de supprimer l'article */
        $hasRight = false;
        $currentUser = $this->get("security.token_storage")->getToken()->getUser();
        $isAdmin = $this->get("security.authorization_checker")->isGranted("ROLE_ADMIN");

        // S'il s'agit d'un article dont l'utilisateur actuel est l'auteur ou si un admin accède à la page
        // on autorise les modifications
        if ($currentUser === $post->getAuthor() || $isAdmin){
            $hasRight = true;
        }

        return $this->render("AppBundle:Post:post.html.twig", [
            "post"         => $post,
            "comment_form" => $form->createView(),
            "hasRight" =>$hasRight
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
            $request->query->getInt('page', 1)
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
            $request->query->getInt("page", 1)
        );

        return $this->render("AppBundle:Post:author.html.twig", [
            "pagination" => $pagination,
            "author"     => $author
        ]);
    }

    /**
     * Crée un nouvel article
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newPostAction(Request $request): Response
    {
        // On rejette tout utilisateur n'ayant pas au moins le rang USER, ie tout utilisateur
        // non connecté
        if (!$this->get("security.authorization_checker")->isGranted('ROLE_USER')){
            throw new AccessDeniedException("Veuillez vous connecter pour écrire un article");
        }

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $post->setCreationDate(new \DateTime());
            $post->setLastUpdateDate(new \DateTime());
            $author = $this->get("security.token_storage")->getToken()->getUser();
            $post->setAuthor($author);
            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();

                $this->addFlash("success", "Votre article a bien été créé");
                return $this->redirectToRoute("post_show", ["slug" => $post->getSlug()]);
            }
        }

        return $this->render("AppBundle:Post:new-post.html.twig", [
            "post_form" => $form->createView()
        ]);
    }

    /**
     *
     * Permet de modifier un post (s'il s'agit de l'auteur ou d'un administrateur)
     *
     * @param Request $request
     * @param int     $idPost
     *
     * @return Response
     */
    public function editAction(Request $request, string $slug): Response{

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository("AppBundle:Post")->findOneBy(["slug" => $slug]);
        $author = $post->getAuthor();
        /** @var User $currentUser */
        $currentUser = $this->get("security.token_storage")->getToken()->getUser();

        if ($currentUser !== $author){
            if (!$this->get("security.authorization_checker")->isGranted("ROLE_ADMIN")){
                throw new AccessDeniedException("Vous ne possédez pas les permissions suffisantes pour accéder à cette page.");
            }

        }

        $form = $this->createForm(PostType::class, $post, ["submit_button" => "Éditer"]);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $post->setLastUpdateDate(new \DateTime());
            if ($form->isValid()){
                $em->flush();

                $this->addFlash("success", "L'article a été modifié avec succès");
                return $this->redirectToRoute("post_show", ["slug" => $post->getSlug()]);
            }
        }
        return $this->render("AppBundle:Post:edit.html.twig", [
            "post_form" => $form->createView()
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function deletePost(): RedirectResponse{

       return $this->redirectToRoute("homepage");
    }
}
