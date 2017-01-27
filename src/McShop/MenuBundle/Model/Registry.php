<?php
namespace McShop\MenuBundle\Model;


abstract class Registry
{
    /** @var array */
    protected $services = [];

    /**
     * @param $alias
     * @param $service
     *
     * @return $this
     */
    public function addService($alias, $service)
    {
        if (!in_array($service, $this->services)) {
            $this->services[$alias] = $service;
        }

        return $this;
    }

    /**
     * @param $alias
     *
     * @return $this
     */
    public function removeService($alias)
    {
        if (isset($this->services[ $alias ])) {
            unset($this->services[$alias]);
        }

        return $this;
    }

    /**
     * @param $alias
     *
     * @return mixed|null
     */
    public function getId($alias)
    {
        if (isset($this->services[$alias])) {
            return $this->services[$alias];
        }

        return null;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->services;
    }
}