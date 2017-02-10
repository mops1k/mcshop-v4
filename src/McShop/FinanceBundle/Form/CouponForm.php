<?php
namespace McShop\FinanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CouponForm
 * @package McShop\FinanceBundle\Form
 */
class CouponForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', NumberType::class, [
                'label'     => 'finance.coupon.amount',
            ])
            ->add('count', NumberType::class, [
                'label'     => 'finance.coupon.count',
            ])
            ->add('dueDate', DateType::class, [
                'label'     => 'finance.coupon.due_date',
                'required'  => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'mc_shop_finance_bundle_coupon_form';
    }
}
