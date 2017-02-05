<?php

namespace McShop\ServersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * ServerCache
 *
 * @ORM\Table(name="server_cache")
 * @ORM\Entity(repositoryClass="McShop\ServersBundle\Repository\ServerCacheRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class ServerCache
{
    use TimestampableEntity;
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
     * @ORM\OneToOne(targetEntity="Server", mappedBy="cache")
     */
    private $server;

    /**
     * @var float
     *
     * @ORM\Column(name="ping", type="float", nullable=true)
     */
    private $ping;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=true)
     */
    private $version;

    /**
     * @var int
     *
     * @ORM\Column(name="protocol", type="integer", nullable=true)
     */
    private $protocol;

    /**
     * @var int
     *
     * @ORM\Column(name="players", type="integer", options={"default": 0}, nullable=true)
     */
    private $players;

    /**
     * @var int
     *
     * @ORM\Column(name="maxPlayers", type="integer", options={"default": 0}, nullable=true)
     */
    private $maxPlayers;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="favicon", type="string", length=255, nullable=true)
     */
    private $favicon;

    /**
     * @var bool
     *
     * @ORM\Column(name="modinfo", type="boolean", nullable=true)
     */
    private $modinfo;

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
     * Set ping
     *
     * @param float $ping
     *
     * @return ServerCache
     */
    public function setPing($ping)
    {
        $this->ping = $ping;

        return $this;
    }

    /**
     * Get ping
     *
     * @return float
     */
    public function getPing()
    {
        return $this->ping;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return ServerCache
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set protocol
     *
     * @param integer $protocol
     *
     * @return ServerCache
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * Get protocol
     *
     * @return integer
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set players
     *
     * @param integer $players
     *
     * @return ServerCache
     */
    public function setPlayers($players)
    {
        $this->players = $players;

        return $this;
    }

    /**
     * Get players
     *
     * @return integer
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set maxPlayers
     *
     * @param integer $maxPlayers
     *
     * @return ServerCache
     */
    public function setMaxPlayers($maxPlayers)
    {
        $this->maxPlayers = $maxPlayers;

        return $this;
    }

    /**
     * Get maxPlayers
     *
     * @return integer
     */
    public function getMaxPlayers()
    {
        return $this->maxPlayers;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ServerCache
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set favicon
     *
     * @param string $favicon
     *
     * @return ServerCache
     */
    public function setFavicon($favicon)
    {
        $this->favicon = $favicon;

        return $this;
    }

    /**
     * Get favicon
     *
     * @return string
     */
    public function getFavicon()
    {
        return $this->favicon;
    }

    /**
     * Set modinfo
     *
     * @param boolean $modinfo
     *
     * @return ServerCache
     */
    public function setModinfo($modinfo)
    {
        $this->modinfo = $modinfo;

        return $this;
    }

    /**
     * Get modinfo
     *
     * @return boolean
     */
    public function getModinfo()
    {
        return $this->modinfo;
    }

    /**
     * Set server
     *
     * @param \McShop\ServersBundle\Entity\Server $server
     *
     * @return ServerCache
     */
    public function setServer(\McShop\ServersBundle\Entity\Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return \McShop\ServersBundle\Entity\Server
     */
    public function getServer()
    {
        return $this->server;
    }
}
