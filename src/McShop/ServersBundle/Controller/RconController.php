<?php
namespace McShop\ServersBundle\Controller;

use McShop\Core\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RconController
 * @package McShop\ServersBundle\Controller
 * @Security("is_granted('ROLE_RCON_MANAGMENT')")
 */
class RconController extends BaseController
{
    public function pageAction()
    {
        $servers = $this->getDoctrine()
            ->getManagerForClass('McShopServersBundle:Server')
            ->getRepository('McShopServersBundle:Server')
            ->findRcon()
        ;

        return $this->render(':Default/Rcon:page.html.twig', [
            'servers'   => $servers,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function commandAction(Request $request)
    {
        $response = [
            'success'   => true,
            'data'      => null,
        ];

        $serverId = $request->get('serverId');
        $command  = $request->get('command');

        if ($serverId === null) {
            $response['success'] = false;
            $response['data']    = $this->get('translator')->trans('server.rcon.select_server');

            return new JsonResponse($response);
        }

        if ($command === null) {
            $response['success'] = false;
            $response['data']    = $this->get('translator')->trans('server.rcon.no_command');

            return new JsonResponse($response);
        }

        $server = $this->getDoctrine()
            ->getManagerForClass('McShopServersBundle:Server')
            ->getRepository('McShopServersBundle:Server')
            ->find($serverId)
        ;

        if ($server === null) {
            $response['success'] = false;
            $response['data']    = $this->get('translator')->trans('server.rcon.unknown_server');

            return new JsonResponse($response);
        }

        $rcon = $this->get('minecraft.server.rcon');
        $rcon
            ->setHost($server->getHost())
            ->setPort($server->getRconPort())
            ->setPassword($server->getRconPassword())
            ->setTimeout(10)
        ;

        if ($rcon->connect()) {
            $rcon->sendCommand($command);
            $response['data'] = $rcon->getResponse($rcon::RESPONSE_FORMATTED);
            $rcon->disconnect();
            return new JsonResponse($response);
        }

        $response['success'] = false;
        $response['data']    = $this->get('translator')->trans('server.rcon.no_connection');

        return new JsonResponse($response);
    }
}
