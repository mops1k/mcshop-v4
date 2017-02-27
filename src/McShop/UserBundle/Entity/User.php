<?php
namespace McShop\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use McShop\FinanceBundle\Entity\Purse;
use McShop\UserBundle\Validation\Constraint\Username;
use Minecraft\SkinView;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="McShop\UserBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(type="guid", nullable=true, unique=true)
     */
    private $uuid = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true, options={"fixed": true})
     */
    private $accessToken = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=41, nullable=true)
     */
    private $serverID = null;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @Username()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true, unique=true)
     */
    private $salt;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    private $locked = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = false;

    /**
     * @var Purse
     * @ORM\OneToOne(targetEntity="McShop\FinanceBundle\Entity\Purse", mappedBy="user")
     */
    private $purse;

    /**
     * @var Role[]
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     */
    private $roles;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $skinAsAvatar = false;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $hdBuyDate;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hdDays;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->active;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add roles
     *
     * @param Role $role
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @param string $uuid
     * @return User
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $accessToken
     * @return User
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getServerID()
    {
        return $this->serverID;
    }

    /**
     * @param string $serverID
     * @return $this
     */
    public function setServerID($serverID)
    {
        $this->serverID = $serverID;
        return $this;
    }

    /**
     * Set purse
     *
     * @param \McShop\FinanceBundle\Entity\Purse $purse
     *
     * @return User
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

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        $avatar = $this->avatar;
        if (!file_exists($avatar)) {
            $avatar = 'assets/img/no-user.png';
        }

        return $avatar;
    }

    /**
     * Set skinAsAvatar
     *
     * @param boolean $skinAsAvatar
     *
     * @return User
     */
    public function setSkinAsAvatar($skinAsAvatar)
    {
        $this->skinAsAvatar = $skinAsAvatar;

        return $this;
    }

    /**
     * Get skinAsAvatar
     *
     * @return boolean
     */
    public function getSkinAsAvatar()
    {
        return $this->skinAsAvatar;
    }

    /**
     * Set hdBuyDate
     *
     * @param \DateTime $hdBuyDate
     *
     * @return User
     */
    public function setHdBuyDate($hdBuyDate)
    {
        $this->hdBuyDate = $hdBuyDate;

        return $this;
    }

    /**
     * Get hdBuyDate
     *
     * @return \DateTime
     */
    public function getHdBuyDate()
    {
        return $this->hdBuyDate;
    }

    /**
     * Set hdDays
     *
     * @param integer $hdDays
     *
     * @return User
     */
    public function setHdDays($hdDays)
    {
        $this->hdDays = $hdDays;

        return $this;
    }

    /**
     * Get hdDays
     *
     * @return integer
     */
    public function getHdDays()
    {
        return $this->hdDays;
    }

    /**
     * @return null|string
     */
    public function getSkin()
    {
        $path = 'minecraft/skins/' . $this->uuid . '.png';
        if (file_exists($path)) {
            return $path;
        }

        return 'minecraft/skins/default.png';
    }

    /**
     * @return null|string
     */
    public function getCloak()
    {
        $path = 'minecraft/cloacks/' . $this->uuid . '.png';
        if (file_exists($path)) {
            return $path;
        }

        return null;
    }

    /**
     * @param string $side
     * @return bool|resource
     */
    public function getSkinPreview($side = 'front')
    {
        $path = 'minecraft/preview/' . $this->uuid . '_' . $side . '.png';
        if (!file_exists($path)) {
            $skinView = new SkinView();
            $skinView
                ->setWaySkin($this->getSkin())
                ->setWayCloak($this->getCloak())
                ->setSaveSkin($path)
                ->setSide($side)
            ;
            $skinView->savePreview();
        }


        return $path;
    }

    /**
     * @return string
     */
    public function getAvatarPath()
    {
        if (!$this->skinAsAvatar) {
            return $this->getAvatar();
        }

        $head = 'minecraft/head/' . $this->uuid . '.png';
        if (!file_exists($head)) {
            $skinView = new SkinView();
            $skinView
                ->setWaySkin($this->getSkin())
                ->setSaveHead($head)
                ->setSize(200)
                ->saveHead()
            ;
        }

        return $head;
    }
}
