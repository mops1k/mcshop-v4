<?php
namespace McShop\ShoppingCartBundle\Menu\Builder;

use McShop\MenuBundle\Model\Common\AbstractBuilder;

class ShoppingCartMenuBuilder extends AbstractBuilder
{
    /**
     * @param string $name
     * @param string $title
     * @param string $url
     * @param array $options
     *
     * @return $this
     */
    public function addItem(string $name, string $title, string $url, array $options = []): self
    {
        $this->menu[ $name ] = $options;
        if ($url !== 'empty') {
            $this->menu[ $name ]['url'] = $url;
        }
        $this->menu[ $name ]['title'] = $this->trans($title);

        return $this;
    }
}