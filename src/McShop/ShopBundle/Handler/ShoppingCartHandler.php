<?php declare(strict_types=1);


namespace McShop\ShopBundle\Handler;


use McShop\ShopBundle\Entity\Item;
use McShop\ShoppingCartBundle\Form\ShoppingCartItemType;

/**
 * Class ShoppingCartHandler
 */
class ShoppingCartHandler implements HandlerInterface
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

        $this->extra = $this->item->getExtra();
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
     * @return HandlerInterface
     */
    public function setItem(Item $item): HandlerInterface
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
