<?php

namespace McShop\ShopBundle\Form;

use Doctrine\Common\Inflector\Inflector;
use McShop\ServersBundle\Entity\Server;
use McShop\ShopBundle\Entity\Item;
use McShop\ShopBundle\Entity\ItemCategory;
use McShop\ShopBundle\Factory\ProductHandlerFactory;
use McShop\ShopBundle\Interfaces\ProductHandlerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    /** @var ProductHandlerFactory */
    private $handlerFactory;

    /**
     * ItemType constructor.
     * @param ProductHandlerFactory $handlerFactory
     */
    public function __construct(ProductHandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $handlers = $this->handlerFactory->getHandlers();
        $builder
            ->add('name', TextType::class, [
                'label'         => 'shop.item.name',
            ])
            ->add('description', TextareaType::class, [
                'label'         => 'shop.item.description',
            ])
            ->add('amount', NumberType::class, [
                'label'         => 'shop.item.amount',
                'data'          => !$builder->getData() ? 1 : $builder->getData()->getAmount(),
                'attr'          => [
                    'min'       => 0,
                ]
            ])
            ->add('price', NumberType::class, [
                'label'         => 'shop.item.price',
                'data'          => !$builder->getData() ? 0 : $builder->getData()->getPrice(),
                'attr'          => [
                    'min'       => 0,
                ]
            ])
            ->add('discount', NumberType::class, [
                'label'         => 'shop.item.discount',
                'data'          => !$builder->getData() ? 0 : $builder->getData()->getSale(),
                'attr'          => [
                    'max'   => 100,
                    'min'   => 0,
                ]
            ])
            ->add('category', EntityType::class, [
                'label'         => 'shop.item.category',
                'class'         => ItemCategory::class,
                'choice_label'  => 'title',
                'required'      => false,
            ])
            ->add('image', FileType::class, [
                'label'         => 'shop.item.image',
                'attr'          => [
                    'class'    => 'filestyle',
                ],
                'mapped'        => false,
                'required'      => false,
            ])
            ->add('server', EntityType::class, [
                'label'         => 'shop.item.server',
                'class'         => Server::class,
                'choice_label'  => function (Server $server) {
                    return $server->getName() . ' (' . $server->getHost() . ':' . $server->getPort() . ')';
                },
                'required'      => true,
            ])
        ;

        $builder
            ->add('additionalFields', FormType::class, [
                'label'         => 'shop.item.additionalFields',
                'required'      => false,
            ]);

        /** @var ProductHandlerInterface $handler */
        foreach ($handlers as $handler) {
            ${$handler->getName()} = $builder->create($handler->getName(), $handler->getFormType(), [
                'label' => 'shop.'.Inflector::tableize(str_replace('Handler', '', $handler->getName())),
            ]);
            ${$handler->getName()}->add('handler', HiddenType::class, [
                'data' => $handler->getName(),
                'mapped' => false,
            ]);
            $builder->get('additionalFields')->add(${$handler->getName()});
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'mc_shop_shop_bundle_item_type';
    }
}
