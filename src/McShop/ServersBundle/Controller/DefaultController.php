<?php

namespace McShop\ServersBundle\Controller;

use McShop\Core\Controller\BaseController;

class DefaultController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function queryAllAction()
    {
        $servers = $this->getDoctrine()
            ->getManagerForClass('McShopServersBundle:Server')
            ->getRepository('McShopServersBundle:Server')
            ->findAll()
        ;

        $query = $this->get('mc_shop.server.query');

        $serversData = [];
        $totalOnline = [
            'players' => 0,
            'maxPlayers' => 0
        ];
        foreach ($servers as $server) {
            $serversData[$server->getId()] = [
                'serverName'    => $server->getName(),
                'status'        => true,
                'data'          => null,
            ];

            if (!$query->update($server)) {
                $serversData[$server->getId()]['status'] = false;
                continue;
            }

            $serversData[$server->getId()]['data']  = $query->getData();

            $totalOnline['players'] += $query->getData()->getPlayers();
            $totalOnline['maxPlayers'] += $query->getData()->getMaxPlayers();
        }

        return $this->render(':Default/Monitoring:all.html.twig', [
            'data'          => $serversData,
            'totalOnline'   => $totalOnline,
        ]);
    }
}
