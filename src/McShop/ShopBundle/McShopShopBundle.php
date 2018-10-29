<?php

namespace McShop\ShopBundle;

use McShop\ShopBundle\DependencyInjection\CompilerPass\HandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class McShopShopBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new HandlerPass());
    }
}
