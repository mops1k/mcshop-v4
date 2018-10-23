<?php

namespace McShop\MenuBundle\DependencyInjection\Compiler;

use McShop\MenuBundle\Service\BuilderCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class BuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $ids = $container->findTaggedServiceIds('mc_shop_menu.builder');
        $collection = $container->getDefinition(BuilderCollection::class);
        foreach ($ids as $id => $tag) {
            $alias = $tag[0]['alias'];
            $collection->addMethodCall('addService', [$alias, $id]);
        }
    }
}
