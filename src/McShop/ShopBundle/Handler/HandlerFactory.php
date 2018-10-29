<?php declare(strict_types=1);

namespace McShop\ShopBundle\Handler;

/**
 * Class HandlerFactory
 */
class HandlerFactory
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * getHandler
     *
     * @param string $name
     *
     * @return HandlerInterface|null
     */
    public function getHandler(string $name): ?HandlerInterface
    {
        if (!isset($this->handlers[$name])) {
            throw new \RuntimeException('Handler this name: '.$name.' does not exists!');
        }

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

    /**
     * handle
     *
     * @param $name
     *
     * @return $this
     */
    public function handle($name): self
    {
        $this->getHandler($name)->handle();

        return $this;
    }
}
