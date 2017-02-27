<?php
namespace McShop\ServersBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\ServersBundle\Entity\Server;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class CrudController extends BaseController
{
    /** @var Form|null */
    private $form = null;
    /** @var Server|null */
    private $server = null;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if (!$this->isGranted('ROLE_SERVER_LIST')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('server.manage.list');
        $servers = $this->getDoctrine()->getManagerForClass('McShopServersBundle:Server')
            ->getRepository('McShopServersBundle:Server')->findAll();

        return $this->render(':Default/Servers:list.html.twig', [
            'servers'   => $servers,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        if (!$this->isGranted('ROLE_SERVER_NEW')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('server.manage.new');
        if ($request->isMethod($request::METHOD_POST)) {
            $this->getForm()->handleRequest($request);
            if ($this->processForm()) {
                $this->addFlash('info', $this->trans('server.new_success'));
                return $this->redirectToRoute('mc_shop_servers_list');
            }
        }

        return $this->render(':Default/Servers:crud.html.twig', [
            'form'  => $this->getForm()->createView(),
        ]);
    }

    /**
     * @param Server $server
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Server $server, Request $request)
    {
        if (!$this->isGranted('ROLE_SERVER_EDIT')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('server.manage.edit');
        $this->setServer($server);

        if ($request->isMethod($request::METHOD_POST)) {
            $this->getForm()->handleRequest($request);
            if ($this->processForm()) {
                $this->addFlash('info', $this->trans('server.edit_success'));
                return $this->redirectToRoute('mc_shop_servers_list');
            }
        }

        return $this->render(':Default/Servers:crud.html.twig', [
            'form'  => $this->getForm()->createView(),
        ]);
    }

    /**
     * @param Server $server
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Server $server)
    {
        if (!$this->isGranted('ROLE_SERVER_REMOVE')) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->getDoctrine()->getManagerForClass(get_class($server));
        $items = $manager->getRepository('McShopShoppingCartBundle:ShoppingCartItem')->findByServer($server);

        foreach ($items as $item) {
            $manager->remove($item);
        }

        $manager->remove($server);
        $manager->flush();

        $this->addFlash('info', $this->trans('server.remove_success'));

        return $this->redirectToRoute('mc_shop_servers_list');
    }

    /**
     * @return Form
     */
    private function getForm()
    {
        if ($this->server === null) {
            $this->server = new Server();
        }

        if ($this->form === null) {
            $this->form = $this->createForm('McShop\ServersBundle\Form\ServerType', $this->server);
        }

        return $this->form;
    }

    /**
     * @param callable|null $callback
     * @return bool
     */
    private function processForm(callable $callback = null)
    {
        if (!$this->getForm()->isValid()) {
            $errors = $this->get('validator')->validate($this->getForm());

            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            return false;
        }

        $server = $this->getForm()->getData();
        $callback !== null ? $callback($server) : null;

        $this->getDoctrine()->getManagerForClass(get_class($server))->persist($server);
        $this->getDoctrine()->getManagerForClass(get_class($server))->flush();

        return true;
    }

    /**
     * @param Server|null $server
     * @return CrudController
     */
    private function setServer($server)
    {
        $this->server = $server;
        return $this;
    }
}
