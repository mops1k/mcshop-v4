<?php
namespace McShop\ShoppingCartBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingCartCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'         => 'shopping_cart.category.title',
            ])
            ->add('parent', EntityType::class, [
                'label'         => 'shopping_cart.category.parent',
                'required'      => false,
                'class'         => 'McShop\ShoppingCartBundle\Entity\ShoppingCartCategory',
                'choice_label'  => 'title',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => 'McShop\ShoppingCartBundle\Entity\ShoppingCartCategory'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_shopping_cart_bundle_shopping_cart_category_type';
    }
}
