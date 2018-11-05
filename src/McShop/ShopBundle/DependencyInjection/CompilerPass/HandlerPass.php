<?php declare(strict_types=1);


namespace McShop\ShopBundle\DependencyInjection\CompilerPass;

use McShop\ShopBundle\Factory\ProductHandlerFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HandlerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        $factoryDefinition = $container->getDefinition(ProductHandlerFactory::class);
        $handlers = $container->findTaggedServiceIds('mc_shop.item_handler');
        foreach ($handlers as $id => $handler) {
            $definition = $container->getDefinition($id);
            $factoryDefinition->addMethodCall('addHandler', [ $definition ]);
        }
    }
}
