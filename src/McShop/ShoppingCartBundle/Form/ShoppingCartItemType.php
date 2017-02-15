<?php

namespace McShop\ShoppingCartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingCartItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('amount')
            ->add('price')
            ->add('sale')
            ->add('category')
            ->add('image')
            ->add('item')
            ->add('type')
            ->add('server')
            ->add('extra')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => 'McShop\ShoppingCartBundle\Entity\ShoppingCartItem'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_shopping_cart_bundle_shopping_cart_item_type';
    }
}
