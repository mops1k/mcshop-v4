<?php
namespace McShop\MenuBundle\Model;

interface BuilderInterface
{
    /**
     * @param string $name
     * @param string $title
     * @param string $url
     * @param array $options
     *
     * @return mixed
     */
    public function addItem(string $name, string $title, string $url, array $options = []);

    /**
     * @return array
     */
    public function getMenu(): array;

    public function clearMenu();
}
