<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options["isAuthenticated"]) {
            $builder->add("content", TextareaType::class, ["label" => false, "attr" => ["placeholder" => "Ajouter un commentaire."]]);
            $builder->add("save", SubmitType::class, ["label" => "Ajouter un commentaire", "attr" => ["class" => "btn btn-primary"]]);
        } else {
            $builder->add("content", TextareaType::class, [
                "label" => false, "attr" => ["placeholder" => "Connectez-vous pour poster un commentaire.", "disabled" => true]
            ]);
            $builder->add("save", SubmitType::class, ["label" => "Ajouter un commentaire", "attr" => ["class" => "btn btn-primary", "disabled" => true]]);
        }


    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => "AppBundle\Entity\Comment",
            "isAuthenticated" => false
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return "appbundle_comment";
    }

}