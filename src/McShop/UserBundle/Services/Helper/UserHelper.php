<?php
namespace McShop\UserBundle\Services\Helper;

use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class UserHelper
 * @package McShop\UserBundle\Services\Helper
 */
class UserHelper
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @var bool
     */
    private $newPassword = false;

    /**
     * @var string
     */
    private $roleName;

    /**
     * UserHelper constructor.
     * @param ManagerRegistry $doctrine
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoder $encoder)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
    }

    /**
     * Save user entity to db
     *
     * @param User $user
     * @param bool $active
     * @param bool $locked
     */
    public function saveUser(User $user, $active = false, $locked = false)
    {
        $role = $this->getEm('McShopUserBundle:Role')
            ->getRepository('McShopUserBundle:Role')
            ->findOneByRole($this->getRoleName())
        ;

        $user
            ->setLocked($locked)
            ->setActive($active)
        ;

        if ($role !== null) {
            $user->addRole($role);
        }

        if ($this->newPassword) {
            $user->setSalt(uniqid(mt_rand()));
            $encodedPassword = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
        }

        $this->getEm(get_class($user))->persist($user);
        $this->getEm(get_class($user))->flush();
    }

    /**
     * @param $class
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    public function getEm($class)
    {
        return $this->doctrine->getManagerForClass($class);
    }

    /**
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * @param string $roleName
     * @return $this
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * @param $bool
     * @return UserHelper
     */
    public function setNewPassword($bool)
    {
        $this->newPassword = $bool;
        return $this;
    }
}