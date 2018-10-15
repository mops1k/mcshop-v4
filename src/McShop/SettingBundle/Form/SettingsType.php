<?php declare(strict_types=1);

namespace McShop\SettingBundle\Form;

use McShop\SettingBundle\Service\SettingHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \ErrorException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['settings'] instanceof SettingHelper) {
            throw new \ErrorException('Wrong setting type!');
        }

        $form = $builder
            ->create('setting', FormType::class, [ 'label' => false ])
            ->add('project_brand', TextType::class, [
                'label' => 'setting.project.brand',
                'data' => $options['settings']->get('project_brand'),
            ])
            ->add('project_title', TextType::class, [
                'label' => 'setting.project.title',
                'data' => $options['settings']->get('project_title'),
            ])
            ->add('hd_price', NumberType::class, [
                'label' => 'setting.hd_price',
                'data' => $options['settings']->get('hd_price', 0),
                'attr' => [
                    'min' => 0,
                    'step' => 5,
                ],
            ])
            ->add('hd_days', NumberType::class, [
                'label' => 'setting.hd_days',
                'data' => $options['settings']->get('hd_days', 0),
                'attr' => [
                    'min' => 0,
                    'step' => 10,
                ],
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'setting.template',
                'data' => $options['settings']->get('template'),
                'choices' => $options['templates'],
            ])
            ->add('launcher_response', TextType::class, [
                'label' => 'setting.launcher_response.title',
                'data' => $options['settings']->get('launcher_response'),
                'trim' => true,
                'attr' => [
                    'placeholder' => 'setting.launcher_response.help'
                ],
            ])
        ;

        $builder->add($form);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'templates' => [],
                'settings' => null,
            ])
            ->setRequired([ 'settings' ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_setting_bundle_setting_type';
    }
}
