<?php

namespace McShop\ShopBundle\Form;

use McShop\ShopBundle\Enum\ShoppingCartItemEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingCartItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \ReflectionException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $enum = new ShoppingCartItemEnum();

        $builder
            ->add('item', TextType::class, [
                'label'         => 'shopping_cart.item.item',
            ])
            ->add('type', ChoiceType::class, [
                'label'         => 'shopping_cart.item.item_type',
                'choices'   => $enum->getTranslationsWithValues(),
            ])
            ->add('extra', TextType::class, [
                'label'         => 'shopping_cart.item.extra',
                'required'      => false,
            ]);
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_shop_bundle_shopping_cart_item_type';
    }
}
