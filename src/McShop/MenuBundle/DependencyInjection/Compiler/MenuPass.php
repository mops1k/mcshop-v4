<?php

namespace McShop\MenuBundle\DependencyInjection\Compiler;

use McShop\MenuBundle\Service\MenuCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class MenuPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $ids = $container->findTaggedServiceIds('mc_shop_menu');
        $collection = $container->getDefinition(MenuCollection::class);
        foreach ($ids as $id => $tag) {
            $alias = $tag[0]['alias'];
            $collection->addMethodCall('addService', [$alias, $id]);
        }
    }
}
