<?php
namespace McShop\MenuBundle\Model;

interface BuilderInterface
{
    /**
     * @param string $title
     * @param string $url
     * @param array  $options
     *
     * @return mixed
     */
    public function addItem($name, $title, $url, array $options = []);

    /**
     * @return array
     */
    public function getMenu();

    public function clearMenu();
}
