<?php
namespace McShop\UserBundle\DependencyInjection\Compiler;

use McShop\UserBundle\Listener\LoginFormTypeListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UpdateSecurityAuthenticationCompiler implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('security.authentication.listener.form');
        $definition->setClass(LoginFormTypeListener::class);
        $definition->addMethodCall('setFormFactory', [ $container->getDefinition('form.factory') ]);
        $definition->addMethodCall('addCustomOptions', [ $container->getParameter('login') ]);
        $definition->addMethodCall('setTranslator', [ $container->getDefinition('translator.default') ]);
    }
}