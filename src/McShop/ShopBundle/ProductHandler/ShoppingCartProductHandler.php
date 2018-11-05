<?php declare(strict_types=1);


namespace McShop\ShopBundle\ProductHandler;


use McShop\ShopBundle\Entity\Item;
use McShop\ShopBundle\Form\ShoppingCartItemType;
use McShop\ShopBundle\Interfaces\ProductHandlerInterface;

/**
 * Class ShoppingCartHandler
 */
class ShoppingCartProductHandler implements ProductHandlerInterface
{
    /** @var array */
    private $extra = [];

    /** @var Item */
    private $item;

    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'ShoppingCartHandler';
    }

    /**
     * parseExtra
     *
     *
     * @throws \Exception
     */
    public function parseExtra(): void
    {
        if (!$this->item) {
            throw new \Exception('Item is not defined');
        }

        $this->extra = $this->item->getAdditionalFields();
    }

    /**
     * getExtraField
     *
     * @param string $fieldName
     *
     * @return null
     */
    public function getExtraField(string $fieldName)
    {
        return $this->extra[$fieldName] ?? null;
    }

    /**
     * setItem
     *
     * @param Item $item
     *
     * @return ProductHandlerInterface
     */
    public function setItem(Item $item): ProductHandlerInterface
    {
        $this->item = $item;

        return $this;
    }

    /**
     * getFormType
     *
     * @return string
     */
    public function getFormType(): string
    {
        return ShoppingCartItemType::class;
    }
}
