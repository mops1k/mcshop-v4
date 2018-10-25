<?php

namespace McShop\UserBundle;

use McShop\UserBundle\DependencyInjection\Compiler\UpdateSecurityAuthenticationCompiler;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class McShopUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new UpdateSecurityAuthenticationCompiler(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
    }
}
