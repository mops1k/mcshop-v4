<?php

namespace McShop\ShoppingCartBundle\Form;

use McShop\ShoppingCartBundle\Entity\ShoppingCartItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'class' => 'McShop\ServersBundle\Entity\Server',
                'choice_label' => function ($server) {
                    return $server->getName() . ' (' . $server->getHost() . ':' . $server->getPort() . ')';
                },
                'required'  => false,
            ])
            ->add('priceFrom', NumberType::class, [
                'required'  => false,
            ])
            ->add('priceTo', NumberType::class, [
                'required'  => false,
            ])
            ->add('type', ChoiceType::class, [
                'required'  => false,
                'choices'   => ShoppingCartItem::getTypes(),
            ])
            ->add('amount', NumberType::class, [
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
