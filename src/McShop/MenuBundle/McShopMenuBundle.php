<?php

namespace McShop\MenuBundle;

use McShop\MenuBundle\DependencyInjection\Compiler\BuilderPass;
use McShop\MenuBundle\DependencyInjection\Compiler\MenuPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class McShopMenuBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new MenuPass());
        $container->addCompilerPass(new BuilderPass());
    }
}
