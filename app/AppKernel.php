<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // Symfony main bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            // Third party bundles
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Matthias\SymfonyConsoleForm\Bundle\SymfonyConsoleFormBundle(),
            new Eschmar\CssInlinerBundle\EschmarCssInlinerBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Evence\Bundle\SoftDeleteableExtensionBundle\EvenceSoftDeleteableExtensionBundle(),
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
//            new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),
            // McShop v4 System bundles
            new McShop\NewsBundle\McShopNewsBundle(),
            new McShop\UserBundle\McShopUserBundle(),
            new McShop\MenuBundle\McShopMenuBundle(),
            new McShop\StaticPageBundle\McShopStaticPageBundle(),
            new McShop\ServersBundle\McShopServersBundle(),
            new McShop\FinanceBundle\McShopFinanceBundle(),
            new McShop\ShoppingCartBundle\McShopShoppingCartBundle(),
            new McShop\SettingBundle\McShopSettingBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
