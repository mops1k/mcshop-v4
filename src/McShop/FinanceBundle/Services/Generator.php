<?php
/**
 * Created by PhpStorm.
 * User: mops1k
 * Date: 09.02.2017
 * Time: 21:32
 */

namespace McShop\FinanceBundle\Services;


use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\FinanceBundle\Entity\Coupon;
use McShop\FinanceBundle\Entity\Purse;
use McShop\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Generator
{
    private $amount;
    private $dueDate;
    private $count = 0;

    /** @var ManagerRegistry */
    private $doctrine;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * Generator constructor.
     * @param ManagerRegistry $doctrine
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Generate random code
     *
     * @return string
     */
    private function generateCode()
    {
        $code = hash('sha512', uniqid(mt_rand()));

        return strtoupper(substr($code, 0, 16));
    }

    /**
     * Generate coupon(s)
     */
    public function generate()
    {
        if ($this->count <= 0) {
            return;
        }

        $manager = $this->doctrine->getManagerForClass('McShopFinanceBundle:Coupon');
        for ($i = 0; $i < $this->count; $i++) {
            $coupon = new Coupon();
            $coupon
                ->setCode($this->generateCode())
                ->setAmount($this->amount)
                ->setActive(true)
                ->setDueDate($this->dueDate)
            ;

            $manager->persist($coupon);
        }

        $manager->flush();
    }

    /**
     * @param $code
     * @return bool
     */
    public function activateCoupon($code)
    {
        $manager = $this->doctrine->getManagerForClass('McShopFinanceBundle:Coupon');

        /** @var Coupon|null $coupon */
        $coupon = $manager->getRepository('McShopFinanceBundle:Coupon')->findOneByCode($code);
        if ($coupon === null || !$coupon->isActive()) {
            return false;
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $purse = $user->getPurse();

        if ($purse === null) {
            $purse = new Purse();
            $purse->setUser($user);
        }

        $coupon
            ->setActivatedBy($user)
            ->setActive(false)
            ->setActivatedAt(new \DateTime())
        ;

        $purse->increaseRealCash($coupon->getAmount());

        $manager->persist($coupon);
        $manager->persist($purse);
        $manager->flush();

        return true;
    }

    /**
     * @param mixed $amount
     * @return Generator
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param mixed $dueDate
     * @return Generator
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @param int $count
     * @return Generator
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }
}
