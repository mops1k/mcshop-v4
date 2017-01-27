<?php
namespace McShop\MenuBundle\Twig;

use McShop\MenuBundle\Factory\MenuFactory;

class MenuExtension extends \Twig_Extension implements \Twig_ExtensionInterface
{
    /** @var MenuFactory */
    protected $menuFactory;
    /** @var  \Twig_Environment */
    protected $twig;

    /**
     * MenuExtension constructor.
     *
     * @param MenuFactory $menuFactory
     */
    public function __construct(MenuFactory $menuFactory, \Twig_Environment $twig)
    {
        $this->menuFactory = $menuFactory;
        $this->twig = $twig;
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