<?php

namespace AppBundle\Controller;

use AppBundle\AppEvents;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Entity\Vote;
use AppBundle\Event\NewPostEvent;
use AppBundle\Form\CommentType;
use AppBundle\Form\PostType;
use AppBundle\Form\VoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class PostController extends Controller
{
    /**
     * Page relative à l'article.
     *
     * @param $id
     *
     * @return Response
     */
    public function showAction(Request $request, string $slug): Response
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);

        if (null === $post) {
            throw $this->createNotFoundException("La page n'existe pas.");
        }

        $isAuthenticated = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, ['isAuthenticated' => $isAuthenticated]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $isAuthenticated) {
            $comment->setCreationDate(new \DateTime());
            $username = $this->get('security.token_storage')->getToken()->getUsername();
            $comment->setUsername($username);
            $comment->setPost($post);
            if ($form->isValid()) {
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('post_show', ['slug' => $post->getSlug(), 'categorySlug' => $post->getCategory()->getSlug()]);
            }
        }

        /** @var bool $hasRight Autorisation de modifier ou de supprimer l'article */
        $hasRight = false;
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');

        // S'il s'agit d'un article dont l'utilisateur actuel est l'auteur ou si un admin accède à la page
        // on autorise les modifications
        if ($currentUser === $post->getAuthor() || $isAdmin) {
            $hasRight = true;
        }

        $vote = new Vote();
        $likeForm = $this->get('form.factory')->create(VoteType::class, $vote, [
            'type'  => 'like',
            'path'  => $this->generateUrl('vote_like', ['ref' => 'post', 'ref_id' => $post->getId()]),
            'value' => $post->getLike()
        ])->createView();

        $dislikeForm = $this->get('form.factory')->create(VoteType::class, $vote, [
            'type'  => 'dislike',
            'path'  => $this->generateUrl('vote_dislike', ['ref' => 'post', 'ref_id' => $post->getId()]),
            'value' => $post->getDislike()
        ])->createView();

        $voteRepository = $em->getRepository('AppBundle:Vote');
        $vote = $voteRepository
            ->findOneBy([
                'ref'   => 'post',
                'refId' => $post->getId(),
                'user'  => $this->getUser()
            ]);
        $class = VoteController::getClass($vote);

        $width = ($post->getLike() + $post->getDislike() === 0) ? 100 : round(($post->getLike() / ($post->getLike() + $post->getDislike())) * 100);

        $voteRepository->updateCount('post', $post->getId());

        return $this->render('AppBundle:Post:post.html.twig', [
            'post'         => $post,
            'comment_form' => $form->createView(),
            'hasRight'     => $hasRight,
            'likeForm'     => $likeForm,
            'dislikeForm'  => $dislikeForm,
            'class'        => $class,
            'width'        => $width
        ]);
    }

    /**
     * Page listant les articles faisant référence à la catégorie.
     *
     * @return Response
     */
    public function categoryAction(Request $request, $categorySlug): Response
    {
        $em = $this->getDoctrine();
        $query = $em->getRepository('AppBundle:Post')->findByCategoryQuery($categorySlug);
        $category = $em->getRepository('AppBundle:Category')->findOneBy(['slug' => $categorySlug]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->render('@App/Post/category.html.twig', [
            'pagination' => $pagination,
            'category'   => $category
        ]);
    }

    /**
     * Liste tous les articles du site.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postsAction(Request $request): Response
    {
        $em = $this->getDoctrine();
        $query = $em->getRepository('AppBundle:Post')
            ->findAllQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->render('AppBundle:Post:posts.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Liste tous les articles correspondant à un utilisateur.
     *
     * @param Request $request
     * @param         $idAuthor
     *
     * @return Response
     */
    public function authorAction(Request $request, $idAuthor): Response
    {
        $em = $this->getDoctrine();
        $query = $em->getRepository('AppBundle:Post')
            ->findByAuthorQuery($idAuthor);

        $author = $em->getRepository('UserBundle:User')->findOneBy(['id' => $idAuthor]);

        if (null === $author) {
            throw $this->createNotFoundException("La page n'existe pas.");
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->render('AppBundle:Post:author.html.twig', [
            'pagination' => $pagination,
            'author'     => $author
        ]);
    }

    /**
     * Crée un nouvel article.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newPostAction(Request $request): Response
    {
        // On rejette tout utilisateur n'ayant pas au moins le rang USER, ie tout utilisateur
        // non connecté
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('Veuillez vous connecter pour écrire un article');
        }

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $post->setCreationDate(new \DateTime());
            $post->setLastUpdateDate(new \DateTime());
            $author = $this->get('security.token_storage')->getToken()->getUser();
            $post->setAuthor($author);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();

                $event = new NewPostEvent($post);
                $this->get('event_dispatcher')->dispatch(AppEvents::NEW_POST, $event);

                $this->addFlash('success', 'Votre article a bien été créé');

                return $this->redirectToRoute('post_show', ['slug' => $post->getSlug(), 'categorySlug' => $post->getCategory()->getSlug()]);
            }
        }

        return $this->render('AppBundle:Post:new-post.html.twig', [
            'post_form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un post (s'il s'agit de l'auteur ou d'un administrateur).
     *
     * @param Request $request
     * @param int     $idPost
     *
     * @return Response
     */
    public function editAction(Request $request, string $slug): Response
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);
        $author = $post->getAuthor();
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($currentUser !== $author) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException('Vous ne possédez pas les permissions suffisantes pour accéder à cette page.');
            }
        }

        $form = $this->createForm(PostType::class, $post, ['submit_button' => 'Éditer']);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $post->setLastUpdateDate(new \DateTime());
            if ($form->isValid()) {
                $em->flush();

                $this->addFlash('success', "L'article a été modifié avec succès");

                return $this->redirectToRoute('post_show', ['slug' => $post->getSlug(), 'categorySlug' => $post->getCategory()->getSlug()]);
            }
        }

        return $this->render('AppBundle:Post:edit.html.twig', [
            'post_form' => $form->createView(),
            'post'      => $post
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAction(int $id): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(['id' => $id]);
        $author = $post->getAuthor();
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        // Si l'utilisateur n'est pas l'auteur de l'article ou n'est pas un admin, on le redirige
        // vers la page principale
        if ($currentUser !== $author) {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('homepage');
            }
        }
        // Sinon on supprime l'article

        $em->remove($post);
        $em->flush();

        $this->addFlash('success', "L'article a correctement été supprimé");

        return $this->redirectToRoute('homepage');
    }
}
