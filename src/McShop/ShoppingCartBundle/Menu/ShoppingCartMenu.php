<?php
namespace McShop\ShoppingCartBundle\Menu;

use McShop\MenuBundle\Model\Common\AbstractMenu;

class ShoppingCartMenu extends AbstractMenu
{
    public function adminMenu()
    {
        $builder = $this->getBuilder();

        $builder
            ->addItem(
                'category',
                'shopping_cart.admin.menu.category',
                '#'
            )
            ->addItem(
                'items',
                'shopping_cart.admin.menu.items',
                '#'
            )
        ;

        return $builder->getMenu();
    }
}
