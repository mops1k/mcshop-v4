<?php

namespace McShop\FinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use McShop\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Purse
 *
 * @ORM\Table(name="purse")
 * @ORM\Entity(repositoryClass="McShop\FinanceBundle\Repository\PurseRepository")
 */
class Purse
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
     * @var float
     *
     * @ORM\Column(name="real_cash", type="float")
     * @Assert\GreaterThanOrEqual(value="0")
     */
    private $realCash = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="game_cash", type="float")
     * @Assert\GreaterThanOrEqual(value="0")
     */
    private $gameCash = 0;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="McShop\UserBundle\Entity\User", inversedBy="purse")
     * @ORM\JoinColumn(nullable=false, unique=true)
     */
    private $user;

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
     * Set realCash
     *
     * @param float $realCash
     * @return Purse
     */
    public function setRealCash($realCash)
    {
        $this->realCash = $realCash;

        return $this;
    }

    /**
     * Get realCash
     *
     * @return float
     */
    public function getRealCash()
    {
        return $this->realCash;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function increaseRealCash($amount)
    {
        $this->realCash += $amount;
        return $this;
    }

    /**
     * @param $amount
     * @return bool
     */
    public function isEnoughMoney($amount)
    {
        return $this->realCash - $amount > 0;
    }

    /**
     * Set gameCash
     *
     * @param float $gameCash
     * @return Purse
     */
    public function setGameCash($gameCash)
    {
        $this->gameCash = $gameCash;

        return $this;
    }

    /**
     * Get gameCash
     *
     * @return float
     */
    public function getGameCash()
    {
        return $this->gameCash;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Purse
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
