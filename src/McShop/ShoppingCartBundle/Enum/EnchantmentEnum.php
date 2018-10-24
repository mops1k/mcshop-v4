<?php declare(strict_types=1);


namespace McShop\ShoppingCartBundle\Enum;

use McShop\Core\Helper\AbstractEnum;

/**
 * Class EnchantmentEnum
 */
class EnchantmentEnum extends AbstractEnum
{
    public const ARMOUR_PROTECTION = 0;
    public const ARMOUR_FIRE_PROTECTION = 1;
    public const ARMOUR_FEATHER_FALLING = 2;
    public const ARMOUR_BLAST_PROTECTION = 3;
    public const ARMOUR_PROJECTILE_PROTECTION = 4;
    public const ARMOUR_RESPIRATION = 5;
    public const ARMOUR_AQUA_AFFINITY = 6;
    public const ARMOUR_THORNS = 7;

    public const WEAPON_SHARPNESS = 16;
    public const WEAPON_SMITE = 17;
    public const WEAPON_BANE_OF_ARTHROPODS = 18;
    public const WEAPON_KNOCKBACK = 19;
    public const WEAPON_FIRE_ASPECT = 20;
    public const WEAPON_LOOTING = 21;

    public const TOOL_EFFICIENCY = 32;
    public const TOOL_SILK_TOUCH = 33;
    public const TOOL_FORTUNE = 35;

    public const BOW_POWER = 48;
    public const BOW_PUNCH = 49;
    public const BOW_FLAME = 50;
    public const BOW_INFINITY = 51;

    public const ALL_UNBREAKING = 34;
}
