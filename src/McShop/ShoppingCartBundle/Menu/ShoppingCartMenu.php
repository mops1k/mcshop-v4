<?php
namespace McShop\ShoppingCartBundle\Menu;

use McShop\MenuBundle\Model\Common\AbstractMenu;

class ShoppingCartMenu extends AbstractMenu
{
    public function adminMenu()
    {
        $builder = $this->getBuilder();

        if ($this->isGranted('ROLE_CATEGORY_LIST')) {
            $builder->addItem(
                'category',
                'shopping_cart.category.menu',
                $this->generateUrlByRouteName('mc_shop_shopping_cart_category_list')
            );
        }

        $builder->addItem(
            'items',
            'shopping_cart.admin.menu.items',
            '#'
        );

        return $builder->getMenu();
    }
}
