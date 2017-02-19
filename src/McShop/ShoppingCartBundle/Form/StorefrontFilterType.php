<?php

namespace McShop\ShoppingCartBundle\Form;

use McShop\ShoppingCartBundle\Entity\ShoppingCartItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorefrontFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('server', EntityType::class, [
                'label' => 'shopping_cart.storefront.filter.server',
                'class' => 'McShop\ServersBundle\Entity\Server',
                'choice_label' => function ($server) {
                    return $server->getName() . ' (' . $server->getHost() . ':' . $server->getPort() . ')';
                },
                'required'  => false,
            ])
            ->add('priceFrom', NumberType::class, [
                'label' => 'shopping_cart.storefront.filter.price_from',
                'required'  => false,
            ])
            ->add('priceTo', NumberType::class, [
                'label' => 'shopping_cart.storefront.filter.price_to',
                'required'  => false,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'shopping_cart.storefront.filter.type',
                'required'  => false,
                'choices'   => ShoppingCartItem::getTypes(),
            ])
            ->add('amount', NumberType::class, [
                'label' => 'shopping_cart.storefront.filter.amount',
                'required'  => false,
            ])
            ->add('isSale', CheckboxType::class, [
                'label' => 'shopping_cart.storefront.filter.is_sale',
                'required'  => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method'    => 'GET'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_shopping_cart_bundle_storefront_filter_type';
    }
}
