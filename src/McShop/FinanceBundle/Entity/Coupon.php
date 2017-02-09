<?php

namespace McShop\FinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use McShop\UserBundle\Entity\User;

/**
 * Coupon
 *
 * @ORM\Table(name="coupon")
 * @ORM\Entity(repositoryClass="McShop\FinanceBundle\Repository\CouponRepository")
 */
class Coupon
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=16, unique=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dueDate", type="datetime", nullable=true)
     */
    private $dueDate;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="McShop\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $activatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activatedAt;


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
     * Set code
     *
     * @param string $code
     *
     * @return Coupon
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Coupon
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return Coupon
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Coupon
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
     * Set activatedBy
     *
     * @param \McShop\UserBundle\Entity\User $activatedBy
     *
     * @return Coupon
     */
    public function setActivatedBy(\McShop\UserBundle\Entity\User $activatedBy = null)
    {
        $this->activatedBy = $activatedBy;

        return $this;
    }

    /**
     * Get activatedBy
     *
     * @return \McShop\UserBundle\Entity\User
     */
    public function getActivatedBy()
    {
        return $this->activatedBy;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if ($this->dueDate === null) {
            return $this->active;
        }

        if ($this->active) {
            $date = new \DateTime();
            return $date < $this->dueDate;
        }

        return $this->active;
    }

    /**
     * Set activatedAt
     *
     * @param \DateTime $activatedAt
     *
     * @return Coupon
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * Get activatedAt
     *
     * @return \DateTime
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }
}
