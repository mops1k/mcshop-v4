<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\Core\Controller\BaseController;

class DefaultController extends BaseController
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

        $totalItems = $this->getDoctrine()
            ->getManagerForClass('McShopShoppingCartBundle:ShoppingCartItem')
            ->getRepository('McShopShoppingCartBundle:ShoppingCartItem')
            ->getTotalCount()
        ;

        $totalBuy = $this->getDoctrine()
            ->getManagerForClass('McShopShoppingCartBundle:BuyHistory')
            ->getRepository('McShopShoppingCartBundle:BuyHistory')
            ->getTotalCount()
        ;

        return $this->render(':Default/ShoppingCart:index.html.twig', [
            'total' => [
                'categories'    => $totalCategories,
                'items'         => $totalItems,
                'buy'           => $totalBuy,
            ],
        ]);
    }
}
