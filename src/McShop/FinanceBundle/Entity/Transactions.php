<?php

namespace McShop\FinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use McShop\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Transactions
 *
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass="McShop\FinanceBundle\Repository\TransactionsRepository")
 */
class Transactions
{
    const STATUS_IN_PROCCESS = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;

    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="smallint")
     */
    private $status = self::STATUS_IN_PROCCESS;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value="0")
     */
    private $cash = 0;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Purse")
     */
    private $purse;

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
     * Set status
     *
     * @param integer $status
     * @return Transactions
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set cash
     *
     * @param float $cash
     * @return Transactions
     */
    public function setCash($cash)
    {
        $this->cash = $cash;

        return $this;
    }

    /**
     * Get cash
     *
     * @return float 
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * Set purse
     *
     * @param \McShop\FinanceBundle\Entity\Purse $purse
     * @return Transactions
     */
    public function setPurse(\McShop\FinanceBundle\Entity\Purse $purse = null)
    {
        $this->purse = $purse;

        return $this;
    }

    /**
     * Get purse
     *
     * @return \McShop\FinanceBundle\Entity\Purse 
     */
    public function getPurse()
    {
        return $this->purse;
    }
}
