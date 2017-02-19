<?php
namespace McShop\ShoppingCartBundle\Menu\Builder;

use McShop\MenuBundle\Model\Common\AbstractBuilder;

class ShoppingCartMenuBuilder extends AbstractBuilder
{
    /**
     * @param $name
     * @param string $title
     * @param string $url
     * @param array $options
     *
     * @return $this
     */
    public function addItem($name, $title, $url, array $options = [])
    {
        $this->menu[ $name ] = $options;
        if ($url !== 'empty') {
            $this->menu[ $name ]['url'] = $url;
        }
        $this->menu[ $name ]['title'] = $this->trans($title);

        return $this;
    }
}