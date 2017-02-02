<?php

namespace McShop\StaticPageBundle\Form;

use McShop\UserBundle\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', TextType::class, [
                'label'     => 'page.form.slug',
            ])
            ->add('title', TextType::class, [
                'label'     => 'page.form.title',
            ])
            ->add('content', TextareaType::class, [
                'label'     => 'page.form.content',
                'attr'      => [
                    'rows'  => 10,
                ],
            ])
            ->add('showInMenu', CheckboxType::class, [
                'label'     => 'page.form.show_in_menu',
                'required'  => false,
            ])
            ->add('role', EntityType::class, [
                'label'     => 'page.form.role',
                'class'     => Role::class,
                'property'  => 'name',
                'required'  => false,
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'McShop\StaticPageBundle\Entity\Page'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mcshop_staticpagebundle_page';
    }


}
