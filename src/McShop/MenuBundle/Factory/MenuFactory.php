<?php
namespace McShop\MenuBundle\Factory;

use McShop\MenuBundle\Exception\MenuComponentNotExistsException;
use McShop\MenuBundle\Model\BuilderInterface;
use McShop\MenuBundle\Model\MenuInterface;
use McShop\MenuBundle\Service\BuilderCollection;
use McShop\MenuBundle\Service\MenuCollection;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuFactory
{
    use ContainerAwareTrait;

    /** @var MenuCollection */
    private $menuCollection;

    /** @var BuilderCollection */
    private $builderCollection;

    /**
     * MenuFactory constructor.
     *
     * @param MenuCollection $menuCollection
     * @param BuilderCollection $builderCollection
     */
    public function __construct(MenuCollection $menuCollection, BuilderCollection $builderCollection)
    {
        $this->menuCollection = $menuCollection;
        $this->builderCollection = $builderCollection;
    }

    /**
     * @param string $alias
     *
     * @return MenuInterface
     * @throws MenuComponentNotExistsException
     * @throws \Exception
     */
    public function get($alias)
    {
        $menuId = $this->menuCollection->getId($alias);
        $builderId = $this->builderCollection->getId($alias);
        if (null === $menuId || null === $builderId) {
            throw new MenuComponentNotExistsException(
                sprintf(
                    'Menu or builder with alias "%s" not found! Both must be set!',
                    $alias
                )
            );
        }

        $menu = $this->container->get($menuId);

        if (!$menu instanceof MenuInterface) {
            throw new \Exception('Menu must implements MenuInterface!');
        }

        $builder = $this->container->get($builderId);

        if (!$builder instanceof BuilderInterface) {
            throw new \Exception('Builder must implements BuilderInterface!');
        }

        $menu->setBuilder($builder);

        return $menu;
    }
}