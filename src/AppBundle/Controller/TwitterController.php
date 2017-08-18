<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TwitterAccount;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TwitterController extends Controller
{
    /**
     * Page d'administration des comptes twitter affichés en page d'accueil
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:TwitterAccount');

        $accounts = $repository->findAll();

        $formsAccounts = [];

        /** @var TwitterAccount $account */
        foreach ($accounts as $account) {
            $formsAccounts[] = $this->createEditTwitterAccountForm($account)->createView();
        }


        $addAccount = $this->addTwitterAccountForm()->createView();

        return $this->render("AppBundle:Twitter:twitter_accounts.html.twig", [
            'formsAccounts' => $formsAccounts,
            'addAccount'    => $addAccount
        ]);
    }

    public function editAccountAction(Request $request, TwitterAccount $account)
    {

    }

    private function createEditTwitterAccountForm(TwitterAccount $account): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_edit_twitter_account', ['id' => $account->getId()]))
            ->setMethod('POST')
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'value' => $account->getUsername()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Modifier"
            ])
            ->getForm();
    }

    public function addTwitterAccountAction(Request $request): RedirectResponse
    {
        $account = new TwitterAccount();
        $form = $this->addTwitterAccountForm($account);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            $this->addFlash('success', "Le compte de {$account->getUsername()} a été ajouté avec succès");
        }

        return $this->redirectToRoute('admin_twitter');
    }

    private function addTwitterAccountForm(?TwitterAccount $account = null): FormInterface
    {
        return $this->createFormBuilder($account)
            ->setAction($this->generateUrl('admin_add_twitter_account'))
            ->setMethod('POST')
            ->add('username', TextType::class, [
                'label' => false,
                'attr'  => [
                    "placeholder" => "Ajouter un compte"
                ]
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Ajouter"
            ])
            ->getForm();
    }
}