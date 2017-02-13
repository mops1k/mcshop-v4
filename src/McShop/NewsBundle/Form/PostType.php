<?php

namespace McShop\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PostType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'label'         => 'news.form.subject',
                'constraints'   => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('lessContent', TextareaType::class, [
                'label'         => 'news.form.less_content',
                'attr'          => [
                    'rows'  => 5
                ],
                'constraints'   => [
                    new Assert\Length(['max' => 500]),
                ],
                'required'      => false,
            ])
            ->add('fullContent', TextareaType::class, [
                'label'         => 'news.form.full_content',
                'attr'          => [
                    'rows'  => 15
                ],
                'constraints'   => [
                    new Assert\NotBlank(),
                ],
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class'    => 'McShop\NewsBundle\Entity\Post',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'mc_shop_news_bundle_post_type';
    }
}
