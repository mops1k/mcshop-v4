<?php
namespace McShop\ShoppingCartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render(':Default/ShoppingCart:index.html.twig');
    }
}
