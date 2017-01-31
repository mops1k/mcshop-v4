<?php

namespace McShop\PexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PexInheritance
 *
 * @ORM\Table(name="pex_inheritance", uniqueConstraints={@ORM\UniqueConstraint(name="child", columns={"child", "parent", "type", "world"})}, indexes={@ORM\Index(name="child_2", columns={"child", "type"}), @ORM\Index(name="parent", columns={"parent", "type"})})
 * @ORM\Entity(repositoryClass="McShop\PexBundle\Repository\PexInheritanceRepository")
 */
class PexInheritance
{
    const TYPE_GROUP = 0;
    const TYPE_USER = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="child", type="string", length=50, nullable=false)
     */
    private $child;

    /**
     * @var string
     *
     * @ORM\Column(name="parent", type="string", length=50, nullable=false)
     */
    private $parent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="world", type="string", length=50, nullable=true)
     */
    private $world;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


}
