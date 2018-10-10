<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\ShoppingCartBundle\Entity\BuyHistory;
use McShop\ShoppingCartBundle\Entity\ShoppingCartCategory;
use McShop\ShoppingCartBundle\Entity\ShoppingCartItem;

class DefaultController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        if (!$this->isGranted('ROLE_SHOPPING_CART_MANAGE')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('shopping_cart.menu.managment_title');

        $totalCategories = $this->getDoctrine()
            ->getManagerForClass(ShoppingCartCategory::class)
            ->getRepository(ShoppingCartCategory::class)
            ->getTotalCount()
        ;

        $totalItems = $this->getDoctrine()
            ->getManagerForClass(ShoppingCartItem::class)
            ->getRepository(ShoppingCartItem::class)
            ->getTotalCount()
        ;

        $totalBuy = $this->getDoctrine()
            ->getManagerForClass(BuyHistory::class)
            ->getRepository(BuyHistory::class)
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
