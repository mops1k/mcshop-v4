<?php
namespace McShop\ServersBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\Core\Twig\Title;
use McShop\ServersBundle\Entity\Server;
use MinecraftRcon\Rcon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Debug\Exception\ContextErrorException;
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
        $this->get(Title::class)->setValue('server.rcon.manage');

        $servers = $this->getDoctrine()
            ->getManagerForClass(Server::class)
            ->getRepository(Server::class)
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

        if (!$serverId) {
            $response['success'] = false;
            $response['data']    = $this->get('translator')->trans('server.rcon.select_server');

            return new JsonResponse($response);
        }

        if (!$command) {
            $response['success'] = false;
            $response['data']    = $this->get('translator')->trans('server.rcon.no_command');

            return new JsonResponse($response);
        }

        $server = $this->getDoctrine()
            ->getManagerForClass(Server::class)
            ->getRepository(Server::class)
            ->find($serverId)
        ;

        if (!$server) {
            $response['success'] = false;
            $response['data']    = $this->get('translator')->trans('server.rcon.unknown_server');

            return new JsonResponse($response);
        }

        $rcon = $this->get(Rcon::class);
        $rcon
            ->setHost($server->getHost())
            ->setPort($server->getRconPort())
            ->setPassword($server->getRconPassword())
            ->setTimeout(10)
        ;

        try {
            $rcon->connect();
            $rcon->sendCommand($command);
            $response['data'] = $rcon->getResponse($rcon::RESPONSE_FORMATTED);
            $rcon->disconnect();
            return new JsonResponse($response);
        } catch (\ErrorException $exception) {
            $response['success'] = false;
            $response['data']    = $this->get('translator')->trans('server.rcon.no_connection').': '.$exception->getMessage();
        }

        return new JsonResponse($response);
    }
}
