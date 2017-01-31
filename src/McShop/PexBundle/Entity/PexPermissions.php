<?php

namespace McShop\PexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PexPermissions
 *
 * @ORM\Table(name="pex_permissions", indexes={@ORM\Index(name="user", columns={"name", "type"}), @ORM\Index(name="world", columns={"world", "name", "type"})})
 * @ORM\Entity(repositoryClass="McShop\PexBundle\Repository\PexPermissionsRepository")
 */
class PexPermissions
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="permission", type="text", length=65535, nullable=false)
     */
    private $permission;

    /**
     * @var string
     *
     * @ORM\Column(name="world", type="string", length=50, nullable=false)
     */
    private $world;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", length=65535, nullable=false)
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


}
