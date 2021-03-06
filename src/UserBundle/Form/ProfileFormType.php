<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProfileFormType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('profilePictureFile', FileType::class, [
            'label' => 'Image de profil',
            'data_class' => null
        ]);
    }

    public function getParent()
    {
        return \FOS\UserBundle\Form\Type\ProfileFormType::class;
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}
