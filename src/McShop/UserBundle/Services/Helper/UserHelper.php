<?php
namespace McShop\UserBundle\Services\Helper;

use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\UserBundle\Entity\Token;
use McShop\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
    private $roleName = null;

    /** @var User */
    private $user;

    /** @var Token */
    private $token;

    /** @var Session */
    private $session;

    /** @var TokenStorageInterface */
    private $tokenStorage;


    /**
     * UserHelper constructor.
     * @param ManagerRegistry $doctrine
     * @param UserPasswordEncoder $encoder
     * @param Session $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        ManagerRegistry $doctrine,
        UserPasswordEncoder $encoder,
        Session $session,
        TokenStorageInterface $tokenStorage
    ) {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param User $user
     * @return UserHelper
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Save user entity to db
     *
     * @param bool $active
     * @param bool $locked
     * @return $this
     */
    public function save($active = false, $locked = false)
    {
        $role = $this->getEm('McShopUserBundle:Role')
            ->getRepository('McShopUserBundle:Role')
            ->findOneByRole($this->getRoleName())
        ;

        $this->user
            ->setLocked($locked)
            ->setActive($active)
        ;

        if ($role !== null) {
            $this->user->addRole($role);
        }

        if ($this->newPassword) {
            $this->user->setSalt(uniqid(mt_rand()));
            $encodedPassword = $this->encoder->encodePassword($this->user, $this->user->getPassword());
            $this->user->setPassword($encodedPassword);
        }

        $this->getEm(get_class($this->user))->persist($this->user);
        $this->getEm(get_class($this->user))->flush();

        return $this;
    }

    public function authUser()
    {
        $usernamePasswordToken = new UsernamePasswordToken(
            $this->user,
            null,
            'main',
            $this->user->getRoles()
        );
        $this->tokenStorage->setToken($usernamePasswordToken);
        $this->session->set('_security_main', serialize($usernamePasswordToken));
    }

    /**
     * Generate new token for user
     * @param int $kind
     * @return $this
     */
    public function generateToken($kind = Token::KIND_REGISTER)
    {
        $this->token = new Token();
        $this->token
            ->setValue(uniqid(mt_rand()))
            ->setActive(true)
            ->setKind($kind)
            ->setUser($this->user)
        ;

        $this->getEm(get_class($this->token))->persist($this->token);
        $this->getEm(get_class($this->token))->flush();

        return $this;
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

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }
}
