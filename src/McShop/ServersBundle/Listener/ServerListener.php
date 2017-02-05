<?php
namespace McShop\ServersBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use McShop\ServersBundle\Entity\Server;
use McShop\ServersBundle\Entity\ServerCache;

/**
 * Class ServerListener
 * @package McShop\ServersBundle\Listener
 */
class ServerListener
{
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof Server) {
            return;
        }

        $cache = new ServerCache();

        $entity->setCache($cache);

        $event->getEntityManager()->persist($cache);
        $event->getEntityManager()->flush();
    }
}