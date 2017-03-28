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
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $buyAt;
}
