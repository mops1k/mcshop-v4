<?php

namespace McShop\ShoppingCartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use McShop\UserBundle\Entity\User;

/**
 * BuyHistory
 *
 * @ORM\Table(name="buy_history")
 * @ORM\Entity(repositoryClass="McShop\ShoppingCartBundle\Repository\BuyHistoryRepository")
 */
class BuyHistory
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
     * @ORM\Column(type="object")
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
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $buyAt;

    /**
     * Get id
     *
     * @return integer
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
     * @return BuyHistory
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set buyAt
     *
     * @param \DateTime $buyAt
     *
     * @return BuyHistory
     */
    public function setBuyAt($buyAt)
    {
        $this->buyAt = $buyAt;

        return $this;
    }

    /**
     * Get buyAt
     *
     * @return \DateTime
     */
    public function getBuyAt()
    {
        return $this->buyAt;
    }

    /**
     * Set item
     *
     * @param \McShop\ShoppingCartBundle\Entity\ShoppingCartItem $item
     *
     * @return BuyHistory
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
     * @return BuyHistory
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
