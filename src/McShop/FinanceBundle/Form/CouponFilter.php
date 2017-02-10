<?php

namespace McShop\FinanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponFilter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', NumberType::class, [
                'label'     => 'finance.coupon.filter.amount',
                'required'  => false,
            ])
            ->add('dueDate', DateType::class, [
                'label'     => 'finance.coupon.filter.due_date',
                'required'  => false,
            ])
            ->add('activatedAt', DateType::class, [
                'label'     => 'finance.coupon.filter.activated_at',
                'required'  => false,
            ])
            ->add('activatedBy', TextType::class, [
                'label'     => 'finance.coupon.filter.activated_by',
                'required'  => false,
            ])
            ->add('active', ChoiceType::class, [
                'label'     => 'finance.coupon.filter.active',
                'required'  => false,
                'choices'   => [
                    'finance.coupon.deactive',
                    'finance.coupon.active',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'method'    => 'GET'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_finance_bundle_coupon_filter';
    }
}
