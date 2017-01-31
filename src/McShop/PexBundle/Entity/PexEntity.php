<?php

namespace McShop\PexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PexEntity
 *
 * @ORM\Table(name="pex_entity", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name", "type"})}, indexes={@ORM\Index(name="default", columns={"default"})})
 * @ORM\Entity(repositoryClass="McShop\PexBundle\Repository\PexEntityRepository")
 */
class PexEntity
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
     * @var boolean
     *
     * @ORM\Column(name="default", type="boolean", nullable=false)
     */
    private $default;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
}
