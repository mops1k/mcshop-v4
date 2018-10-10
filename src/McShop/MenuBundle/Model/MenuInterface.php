<?php
namespace McShop\MenuBundle\Model;

interface MenuInterface
{
    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return array
     */
    public function get(string $name, array $arguments = []): array;

    /**
     * @param BuilderInterface $builder
     *
     * @return $this
     */
    public function setBuilder(BuilderInterface $builder): self;
}