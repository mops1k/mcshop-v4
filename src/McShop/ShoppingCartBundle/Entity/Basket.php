<?php

namespace McShop\ShoppingCartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use McShop\UserBundle\Entity\User;

/**
 * Basket
 *
 * @ORM\Table(name="basket")
 * @ORM\Entity(repositoryClass="McShop\ShoppingCartBundle\Repository\BasketRepository")
 */
class Basket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ShoppingCartItem
     *
     * @ORM\ManyToOne(targetEntity="McShop\ShoppingCartBundle\Entity\ShoppingCartItem")
     */
    private $item;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="McShop\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Basket
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set item
     *
     * @param \McShop\ShoppingCartBundle\Entity\ShoppingCartItem $item
     *
     * @return Basket
     */
    public function setItem(\McShop\ShoppingCartBundle\Entity\ShoppingCartItem $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \McShop\ShoppingCartBundle\Entity\ShoppingCartItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set user
     *
     * @param \McShop\UserBundle\Entity\User $user
     *
     * @return Basket
     */
    public function setUser(\McShop\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \McShop\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
