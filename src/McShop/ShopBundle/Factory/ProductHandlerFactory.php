<?php declare(strict_types=1);

namespace McShop\ShopBundle\Factory;

use McShop\ShopBundle\Entity\Item;
use McShop\ShopBundle\Interfaces\ProductHandlerInterface;

/**
 * Class HandlerFactory
 */
class ProductHandlerFactory
{
    /**
     * @var ProductHandlerInterface[]
     */
    private $handlers = [];

    /**
     * getHandler
     *
     * @param string $name
     *
     * @return ProductHandlerInterface|null
     */
    public function getHandler(string $name, ?Item $item): ?ProductHandlerInterface
    {
        if (!isset($this->handlers[$name])) {
            throw new \RuntimeException('Handler this name: '.$name.' does not exists!');
        }

        $this->handlers[$name]->setItem($item);
        return $this->handlers[$name];
    }

    /**
     * addHandler
     *
     * @param ProductHandlerInterface $handler
     *
     * @return $this
     */
    public function addHandler(ProductHandlerInterface $handler): self
    {
        if (in_array($handler, $this->handlers, true)) {
            throw new \RuntimeException('Handler: '.$handler->getName().' already exists!');
        }

        $this->handlers[$handler->getName()] = $handler;

        return $this;
    }

    /**
     * @return ProductHandlerInterface|mixed
     */
    public function getHandlers()
    {
        return $this->handlers;
    }
}
