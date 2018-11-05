<?php declare(strict_types=1);

namespace McShop\ShopBundle\Interfaces;

use McShop\ShopBundle\Entity\Item;

/**
 * Interface ProductHandlerInterface
 * @package McShop\ShopBundle\Interfaces
 */
interface ProductHandlerInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    public function parseExtra(): void;

    /**
     * @param string $fieldName
     * @return mixed
     */
    public function getExtraField(string $fieldName);

    /**
     * @param Item $item
     * @return ProductHandlerInterface
     */
    public function setItem(Item $item): ProductHandlerInterface;

    /**
     * @return string
     */
    public function getFormType(): string;
}
