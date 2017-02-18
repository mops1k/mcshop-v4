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
                $this->generateUrlByRouteName('mc_shop_shopping_cart_manage_category_list')
            );
        }

        if ($this->isGranted('ROLE_ITEM_LIST')) {
            $builder->addItem(
                'items',
                'shopping_cart.item.menu',
                $this->generateUrlByRouteName('mc_shop_shopping_cart_manage_item_list')
            );
        }

        return $builder->getMenu();
    }
}
