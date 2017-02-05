<?php
/**
 * Created by PhpStorm.
 * User: mops1k
 * Date: 05.02.2017
 * Time: 13:53
 */

namespace McShop\ServersBundle\Services;


use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\ServersBundle\Entity\Server;
use McShop\ServersBundle\Entity\ServerCache;
use MinecraftServerStatus\MinecraftServerStatus;

class ServerStatusUpdate
{
    /** @var ManagerRegistry */
    private $doctrine;

    /** @var MinecraftServerStatus */
    private $status;

    /** @var ServerCache */
    private $data;

    /**
     * ServerStatusUpdate constructor.
     * @param ManagerRegistry $doctrine
     * @param MinecraftServerStatus $status
     */
    public function __construct(ManagerRegistry $doctrine, MinecraftServerStatus $status)
    {
        $this->doctrine = $doctrine;
        $this->status = $status;
    }

    public function update(Server $server)
    {
        $this->data = $server->getCache();

        if (!$this->needToUpdate($this->data)) {
            return true;
        }

        $this->status
            ->setHost($server->getHost())
            ->setPort($server->getPort())
        ;

        if (!$this->status->query()) {
            return false;
        }

        $data = $this->status->getData();
        $this->data
            ->setPing($data->getPing())
            ->setPlayers($data->getPlayers())
            ->setMaxPlayers($data->getMaxPlayers())
            ->setVersion($data->getVersion())
            ->setDescription($data->getDescription())
            ->setProtocol($data->getProtocol())
        ;

        $this->doctrine->getManagerForClass(get_class($this->data))->persist($this->data);
        $this->doctrine->getManagerForClass(get_class($this->data))->flush();

        return true;
    }

    /**
     * @param ServerCache $cache
     * @return bool
     */
    private function needToUpdate(ServerCache $cache)
    {
        $lastUpdate = $cache->getUpdatedAt();
        $lastUpdate->add(\DateInterval::createFromDateString('1 minute'));
        $currentDate = new \DateTime('NOW');

        return $currentDate > $lastUpdate;
    }

    /**
     * @return ServerCache
     */
    public function getData()
    {
        return $this->data;
    }
}