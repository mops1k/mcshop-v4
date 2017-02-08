<?php

namespace McShop\ServersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation\onSoftDelete;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Server
 *
 * @ORM\Table(name="server")
 * @ORM\Entity(repositoryClass="McShop\ServersBundle\Repository\ServerRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Server
{
    use SoftDeleteableEntity;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @var int
     *
     * @ORM\Column(name="port", type="integer", options={"default": 25565}, nullable=true)
     */
    private $port = 25565;

    /**
     * @var int
     * @ORM\Column(name="rcon_port", type="integer", nullable=true)
     */
    private $rconPort;

    /**
     * @var string
     * @ORM\Column(name="rcon_password", type="string", nullable=true)
     */
    private $rconPassword;


    /**
     * @var string
     *
     * @ORM\Column(name="shopping_cart_id", type="string", length=255, unique=true)
     */
    private $shoppingCartId;

    /**
     * @var ServerCache
     * @ORM\OneToOne(targetEntity="McShop\ServersBundle\Entity\ServerCache", inversedBy="server")
     * @ORM\JoinColumn(nullable=true)
     * @onSoftDelete(type="CASCADE")
     */
    private $cache;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set host
     *
     * @param string $host
     *
     * @return Server
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set port
     *
     * @param integer $port
     *
     * @return Server
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Server
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
     * Set shoppingCartId
     *
     * @param string $shoppingCartId
     *
     * @return Server
     */
    public function setShoppingCartId($shoppingCartId)
    {
        $this->shoppingCartId = $shoppingCartId;

        return $this;
    }

    /**
     * Get shoppingCartId
     *
     * @return string
     */
    public function getShoppingCartId()
    {
        return $this->shoppingCartId;
    }

    /**
     * Set cache
     *
     * @param \McShop\ServersBundle\Entity\ServerCache $cache
     *
     * @return Server
     */
    public function setCache(\McShop\ServersBundle\Entity\ServerCache $cache = null)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get cache
     *
     * @return \McShop\ServersBundle\Entity\ServerCache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set rconPort
     *
     * @param integer $rconPort
     *
     * @return Server
     */
    public function setRconPort($rconPort)
    {
        $this->rconPort = $rconPort;

        return $this;
    }

    /**
     * Get rconPort
     *
     * @return integer
     */
    public function getRconPort()
    {
        return $this->rconPort;
    }

    /**
     * Set rconPassword
     *
     * @param string $rconPassword
     *
     * @return Server
     */
    public function setRconPassword($rconPassword)
    {
        $this->rconPassword = $rconPassword;

        return $this;
    }

    /**
     * Get rconPassword
     *
     * @return string
     */
    public function getRconPassword()
    {
        return $this->rconPassword;
    }
}
