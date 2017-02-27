<?php
namespace McShop\MenuBundle\Twig;

use McShop\MenuBundle\Factory\MenuFactory;
use McShop\SettingBundle\Service\SettingHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuExtension extends \Twig_Extension implements \Twig_ExtensionInterface
{
    /** @var MenuFactory */
    protected $menuFactory;
    /** @var \Twig_Environment */
    protected $twig;
    /** @var SettingHelper */
    protected $setting;
    /** @var Filesystem */
    protected $filesystem;
    /** @var string */
    protected $rootDir;

    /**
     * MenuExtension constructor.
     *
     * @param MenuFactory $menuFactory
     * @param \Twig_Environment $twig
     * @param SettingHelper $settingHelper
     * @param Filesystem $filesystem
     * @param $rootDir
     */
    public function __construct(
        MenuFactory $menuFactory,
        \Twig_Environment $twig,
        SettingHelper $settingHelper,
        Filesystem $filesystem,
        $rootDir
    ) {
        $this->menuFactory = $menuFactory;
        $this->twig = $twig;
        $this->setting = $settingHelper;
        $this->filesystem = $filesystem;
        $this->rootDir = $rootDir;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('renderMenu', [ $this, 'render' ]),
        ];
    }

    /**
     * Render menu from 'alias.name' with 'template' name and 'arguments' for menu
     *
     * @param string $id        'alias.name'
     * @param string $template  'template.html.twig'
     * @param array  $arguments 'array of arguments'
     */
    public function render($id, $template, array $arguments = [])
    {
        list($alias, $name) = explode('.', $id);
        $menu = $this->menuFactory->get($alias)->get($name, $arguments);

        if (!$this->filesystem->exists(
            $this->rootDir . '/Resources/views/' . $this->setting->get('template')
        )) {
            throw new NotFoundHttpException(
                sprintf('Template "%s" does not exists!', $this->setting->get('template'))
            );
        }

        $template = preg_replace_callback('/^:(\w+)\//i', function () {
            return sprintf(':%s/', $this->setting->get('template'));
        }, $template, 1);

        $this->twig->display($template, [
            'menu' => $menu
        ]);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'mc_shop_menu';
    }
}