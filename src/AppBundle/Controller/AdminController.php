<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 07/08/2017
 * Time: 18:06.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class AdminController extends Controller
{
    public function dashboardAction(): Response
    {
        return $this->render('@App/Admin/dashboard.html.twig');
    }

    /**
     * Page du panneau de configuration propre aux utilisateurs.
     *
     * @return Response
     */
    public function usersAction(Request $request): Response
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserBundle:User');
        $paginator = $this->get('knp_paginator');

        $query = $repository->createQueryBuilder('u')
            ->orderBy('u.username', 'desc')
            ->getQuery();

        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        $deleteForms = [];
        $roleForms = [];
        /** @var User $user */
        foreach ($users as $user) {
            $deleteForms[$user->getId()] = $this->createUserDeleteForm($user)->createView();
            $roleForms[$user->getId()] = $this->createEditRoleForm($user)->createView();
        }

        $searchForm = $this->createSearchForm()->createView();

        return $this->render('AppBundle:Admin:dashboard-users.html.twig', [
            'users'        => $users,
            'deletesForms' => $deleteForms,
            'roleForms'    => $roleForms,
            'searchForm'   => $searchForm
        ]);
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return RedirectResponse
     */
    public function deleteUserAction(Request $request, User $user): RedirectResponse
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createUserDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $this->addFlash('success', 'Utilisateur supprimé');
            $em->flush();
        }

        return $this->redirectToRoute('admin_dashboard_users');
    }

    /**
     * @param User $user
     *
     * @return FormInterface
     */
    private function createUserDeleteForm(User $user): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    public function deleteCategoryAction(Request $request, Category $category): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->json('Method not allowed');
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json('You are not allowed to delete a category');
        }

        $form = $this->createCategoryDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $name = $category->getName();
            $em->remove($category);

            return $this->json("The category $name has been removed successfully.");
        }

        return $this->json('The form was not submitted.');
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editRoleAction(Request $request, User $user)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->json('Method not allowed');
        }

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->json('You are not allowed to change this property');
        }
        $form = $this->createEditRoleForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user->setRoles([$data['roles']]);
            $em->flush();

            return $this->json("Role changed successfully. {$user->getUsername()} has now the role {$user->getRoles()[0]}");
        }

        return $this->json('The form was not submitted');
    }

    public function searchUserAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->json('Method not allowed');
        }

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->json('You are not allowed to change this property');
        }
        $form = $this->createSearchForm();
        $form->handleRequest($request);
        dump($form);
        die();

        return $this->json($form->getData());
    }

    /**
     * @param User $user
     *
     * @return FormInterface
     */
    public function createEditRoleForm(User $user): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_edit_role', ['id' => $user->getId()]))
            ->setMethod('POST')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Admin'       => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN'
                ],
                'label'   => false,
                'data'    => $user->getRoles()[0]
            ])
            ->getForm();
    }

    /**
     * @return FormInterface
     */
    public function createSearchForm(): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('super_admin_search_user'))
            ->setMethod('POST')
            ->add('search', TextType::class, [
                'mapped' => false,
                'label'  => false,
                'attr'   => [
                    'placeholder' => 'Chercher un utilisateur'
                ]
            ])
            ->getForm();
    }
}
