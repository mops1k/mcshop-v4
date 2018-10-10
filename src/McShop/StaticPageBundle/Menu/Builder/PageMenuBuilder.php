<?php
namespace McShop\StaticPageBundle\Menu\Builder;

use McShop\MenuBundle\Model\Common\AbstractBuilder;

class PageMenuBuilder extends AbstractBuilder
{
    /**
     * Add item to menu
     *
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
        $this->menu[ $name ]['title'] = $title;

        return $this;
    }
}