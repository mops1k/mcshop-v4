<?php

namespace McShop\ShoppingCartBundle\Form;

use McShop\ShoppingCartBundle\Entity\ShoppingCartItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingCartItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('amount', NumberType::class)
            ->add('price', NumberType::class)
            ->add('sale', NumberType::class, [
                'data'          => 0,
                'attr'          => [
                    'max'   => 100,
                    'min'   => 0,
                ]
            ])
            ->add('category', EntityType::class, [
                'class'         => 'McShop\ShoppingCartBundle\Entity\ShoppingCartCategory',
                'choice_label'  => 'title',
                'required'      => false,
            ])
            ->add('image', FileType::class)
            ->add('item', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices'   => ShoppingCartItem::getTypes(),
            ])
            ->add('server', EntityType::class, [
                'class'         => 'McShop\ServersBundle\Entity\Server',
                'choice_label'  => 'name',
                'required'      => true,
            ])
            ->add('extra', TextType::class)
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
