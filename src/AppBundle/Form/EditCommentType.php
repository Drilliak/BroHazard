<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 20/08/17
 * Time: 18:46.
 */

namespace AppBundle\Form;

use AppBundle\Entity\Vote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->setAction($options['path']);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'path'       => null,
        ]);
        $resolver->setDefined(['path']);
    }

    public function getBlockPrefix()
    {
        return 'app_vote';
    }
}
