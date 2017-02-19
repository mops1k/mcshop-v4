<?php
namespace McShop\UserBundle\Menu\Builder;

use McShop\MenuBundle\Model\Common\AbstractBuilder;

class UserMenuBuilder extends AbstractBuilder
{
    /**
     * @var null
     */
    protected $lastRootName = null;
    /** @var int */
    private $currentDivider = 0;

    /**
     * Add root item
     *
     * @param $name
     * @param $title
     *
     * @return $this
     */
    public function addRootItem($name, $title, $url = null, array $options = [])
    {
        $this->menu[ $name ] = $options;
        $this->menu[ $name ]['title'] = $this->trans($title);
        if (null != $url) {
            $this->menu[ $name ]['url'] = $url;
        }
        $this->lastRootName = $name;

        return $this;
    }

    public function addDivider()
    {
        $this->addItem('divider_' . $this->currentDivider++, '', 'empty', ['class' => 'divider']);
        return $this;
    }


    public function addHeader($title, array $parameters = [])
    {
        $this->addItem('header', $this->trans($title, $parameters), 'empty', ['class' => 'dropdown-header']);
        return $this;
    }

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
        if (!isset($options['rootName'])) {
            $root = &$this->menu[ $this->lastRootName ]['items'];
        } else {
            $root = &$this->menu[ $options['rootName'] ]['items'];
            unset($options['rootName']);
        }

        $root[ $name ] = $options;
        if ($url !== 'empty') {
            $root[ $name ]['url'] = $url;
        }
        $root[ $name ]['title'] = $this->trans($title);

        return $this;
    }
}