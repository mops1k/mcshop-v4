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
            ->add('name', TextType::class, [
                'label'         => 'shopping_cart.item.name',
            ])
            ->add('amount', NumberType::class, [
                'label'         => 'shopping_cart.item.amount',
                'data'          => $builder->getData() === null ? 1 : $builder->getData()->getAmount(),
                'attr'          => [
                    'min'       => 0,
                ]
            ])
            ->add('price', NumberType::class, [
                'label'         => 'shopping_cart.item.price',
                'data'          => $builder->getData() === null ? 0 : $builder->getData()->getPrice(),
                'attr'          => [
                    'min'       => 0,
                ]
            ])
            ->add('sale', NumberType::class, [
                'label'         => 'shopping_cart.item.sale',
                'data'          => $builder->getData() === null ? 0 : $builder->getData()->getSale(),
                'attr'          => [
                    'max'   => 100,
                    'min'   => 0,
                ]
            ])
            ->add('category', EntityType::class, [
                'label'         => 'shopping_cart.item.category',
                'class'         => 'McShop\ShoppingCartBundle\Entity\ShoppingCartCategory',
                'choice_label'  => 'title',
                'required'      => false,
            ])
            ->add('image', FileType::class, [
                'label'         => 'shopping_cart.item.image',
                'attr'          => [
                     'class'    => 'filestyle',
                ],
                'mapped'        => false,
                'required'      => false,
            ])
            ->add('item', TextType::class, [
                'label'         => 'shopping_cart.item.item',
            ])
            ->add('type', ChoiceType::class, [
                'label'         => 'shopping_cart.item.item_type',
                'choices'   => ShoppingCartItem::getTypes(),
            ])
            ->add('server', EntityType::class, [
                'label'         => 'shopping_cart.item.server',
                'class'         => 'McShop\ServersBundle\Entity\Server',
                'choice_label'  => function ($server) {
                    return $server->getName() . ' (' . $server->getHost() . ':' . $server->getPort() . ')';
                },
                'required'      => true,
            ])
            ->add('extra', TextType::class, [
                'label'         => 'shopping_cart.item.extra',
                'required'      => false,
            ])
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
