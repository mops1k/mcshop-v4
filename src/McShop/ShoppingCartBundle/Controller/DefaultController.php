<?php
namespace McShop\ShoppingCartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $totalCategories = $this->getDoctrine()
            ->getManagerForClass('McShopShoppingCartBundle:ShoppingCartCategory')
            ->getRepository('McShopShoppingCartBundle:ShoppingCartCategory')
            ->getTotalCount()
        ;

        return $this->render(':Default/ShoppingCart:index.html.twig', [
            'total' => [
                'categories'    => $totalCategories,
            ],
        ]);
    }
}
