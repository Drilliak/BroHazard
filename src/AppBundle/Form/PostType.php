<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 31/07/2017
 * Time: 00:05
 */

namespace AppBundle\Form;


use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, ["label" => "Titre"])
            ->add("category", EntityType::class, [
                "class"       => "AppBundle\Entity\Category",
                "placeholder" => "Sélectionner la catégorie",
                "label"       => "Catégorie"
            ])
            ->add("content", CKEditorType::class, ["label" => false])
            ->add("save", SubmitType::class, ["label" => $options["submit_button"], "attr" => ["class" => "btn btn-primary"]]);


    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class"    => "AppBundle\Entity\Post",
            "submit_button" => "Créer"
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return "appbundle_post";
    }

}