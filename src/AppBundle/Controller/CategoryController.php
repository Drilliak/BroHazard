<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 13/08/2017
 * Time: 22:17
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{

    /**
     * Page qui permet de gérer les catégories depuis l'administration
     * Role admin requis
     */
    public function showAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Category');
        $paginator = $this->get('knp_paginator');

        $query = $repository->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery();

        $categories = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            30
        );

        $updateForms = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $updateForms[$category->getId()] = $this->createUpdateForm($category)->createView();
        }

        $newCategoryForm = $this->newCategoryForm()->createView();

        return $this->render('@App/Category/categories.html.twig', [
            "categories"  => $categories,
            "updateForms" => $updateForms,
            "newCategoryForm" => $newCategoryForm
        ]);
    }

    /**
     * Permet de modifier le nom d'une catégorie
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return RedirectResponse
     */
    public function updateAction(Request $request, Category $category): RedirectResponse
    {

        $form = $this->createUpdateForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $name = $form->getData()['name'];
            $category->setName($name);
            $this->addFlash('success', 'Catégorie modifié avec succès');
            $em->flush();
        }

        return $this->redirectToRoute('admin_categories');
    }


    /**
     * @param Category $category
     *
     * @return FormInterface
     */
    private function createUpdateForm(Category $category): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_update_category', ['id' => $category->getId()]))
            ->setMethod('POST')
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'value' => $category->getName()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Modifier"
            ])
            ->getForm();
    }

    /**
     * Soumission du formulaire de création d'une catégorie
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createAction(Request $request): RedirectResponse
    {
        $category = new Category();
        $form = $this->newCategoryForm($category);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', "Catégorie créée avec succès");
        }

        return $this->redirectToRoute("admin_categories");
    }

    /**
     * Création du formulaire de soumission d'une catégorie
     *
     * @return FormInterface
     */
    private function newCategoryForm(?Category $category = null): FormInterface
    {
        return $this->createFormBuilder($category)
            ->setAction($this->generateUrl('admin_new_category'))
            ->add('name', TextType::class, [
                "label" => false,
                "attr"  => [
                    "placeholder" => "Nouvelle catégorie"
                ]
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Créer"
            ])
            ->getForm();
    }

}