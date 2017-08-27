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

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->setAction($options['path'])
            ->add('submit', SubmitType::class, [
                'attr'  => [
                    'class' => "vote-btn vote-{$options['type']}",
                ],
                'label' => $options['value']
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['icon'] = $options['icon'];
        $view->vars['idSpan'] = $options['idSpan'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'     => Vote::class,
            'icon'           => null,
            'path'           => null,
            'type'           => null,
            'value'          => 0,
            'idSpan'         => null,
        ]);
        $resolver->setDefined(['icon', 'path', 'type', 'value', 'idSpan']);
    }

    public function getBlockPrefix()
    {
        return 'app_vote';
    }
}
