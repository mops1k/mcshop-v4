<?php declare(strict_types=1);


namespace McShop\ShopBundle\Enum;


use McShop\Core\Helper\AbstractEnum;

class ShoppingCartItemEnum extends AbstractEnum
{
    const TYPE_ITEM = "item";
    const TYPE_MONEY = "money";
    const TYPE_RGOWN = "rgown";
    const TYPE_RGMEM = "rgmem";
    const TYPE_PERMGROUP = "permgroup";
    const TYPE_PERM = "perm";
}
