<?php

namespace McShop\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as FormPasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type'  => FormPasswordType::class,
            'invalid_message' => 'validation.password.must_match',
            'first_options' => ['label' => 'form.registration.password'],
            'second_options' => ['label' => 'form.registration.re_password'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => 'McShop\UserBundle\Entity\User'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_user_bundle_password_type';
    }
}
