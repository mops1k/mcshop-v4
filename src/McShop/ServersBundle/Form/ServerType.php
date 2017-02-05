<?php

namespace McShop\ServersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'server.name',
                'required'  => false,
            ])
            ->add('host', TextType::class, [
                'label' => 'server.host',
                'attr'      => [
                    'placeholder' => '127.0.0.1'
                ],
            ])
            ->add('port', NumberType::class, [
                'label' => 'server.port',
                'attr'      => [
                    'placeholder' => 25565
                ],
            ])
            ->add('shoppingCartId', TextType::class, [
                'label' => 'server.shopping_cart_id',
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'McShop\ServersBundle\Entity\Server'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mcshop_serversbundle_server';
    }


}
