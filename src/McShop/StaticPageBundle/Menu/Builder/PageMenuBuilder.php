<?php
namespace McShop\StaticPageBundle\Menu\Builder;

use McShop\MenuBundle\Model\Common\AbstractBuilder;

class PageMenuBuilder extends AbstractBuilder
{
    /**
     * Add item to menu
     *
     * @param       $title
     * @param       $url
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
        $this->menu[ $name ]['title'] = $title;

        return $this;
    }
}