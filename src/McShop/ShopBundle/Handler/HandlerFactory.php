<?php declare(strict_types=1);

namespace McShop\ShopBundle\Handler;

use McShop\ShopBundle\Entity\Item;

/**
 * Class HandlerFactory
 */
class HandlerFactory
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    /**
     * getHandler
     *
     * @param string $name
     *
     * @return HandlerInterface|null
     */
    public function getHandler(string $name, ?Item $item): ?HandlerInterface
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
     * @param HandlerInterface $handler
     *
     * @return $this
     */
    public function addHandler(HandlerInterface $handler): self
    {
        if (in_array($handler, $this->handlers, true)) {
            throw new \RuntimeException('Handler: '.$handler->getName().' already exists!');
        }

        $this->handlers[$handler->getName()] = $handler;

        return $this;
    }
}
