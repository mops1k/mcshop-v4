<?php

namespace McShop\ServersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('McShopServersBundle:Default:index.html.twig');
    }
}
