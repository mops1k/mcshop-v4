<?php

namespace McShop\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="McShop\UserBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, unique=true)
     */
    private $role;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="children_roles")
     * @ORM\JoinColumn(name="parent_id", nullable=true)
     */
    private $parent_role;

    /**
     * @var Role[]
     *
     * @ORM\OneToMany(targetEntity="Role", mappedBy="parent_role")
     */
    private $children_roles;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children_roles = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set parent_role
     *
     * @param Role $parentRole
     * @return Role
     */
    public function setParentRole(Role $parentRole = null)
    {
        $this->parent_role = $parentRole;

        return $this;
    }

    /**
     * Get parent_role
     *
     * @return Role
     */
    public function getParentRole()
    {
        return $this->parent_role;
    }

    /**
     * Add children_roles
     *
     * @param Role $childrenRole
     * @return Role
     */
    public function addChildrenRole(Role $childrenRole)
    {
        $this->children_roles[] = $childrenRole;

        return $this;
    }

    /**
     * Remove children_roles
     *
     * @param Role $childrenRole
     */
    public function removeChildrenRole(Role $childrenRole)
    {
        $this->children_roles->removeElement($childrenRole);
    }

    /**
     * Get children_roles
     *
     * @return Role[]
     */
    public function getChildrenRoles()
    {
        return $this->children_roles;
    }

    /**
     * Add users
     *
     * @param User $user
     * @return Role
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove users
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }
}
