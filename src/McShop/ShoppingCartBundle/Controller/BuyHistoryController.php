<?php
namespace McShop\ShoppingCartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BuyHistoryController extends Controller
{
    public function indexAction(Request $request)
    {
        $history = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:BuyHistory')
            ->getRepository('McShopShoppingCartBundle:BuyHistory')->findAllAsPagination();
        $history
            ->setCurrentPage($request->get('page', 1))
            ->setMaxPerPage($request->get('per_page', 50))
        ;

        $income = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:BuyHistory')
            ->getRepository('McShopShoppingCartBundle:BuyHistory')->getTotalIncome();

        return $this->render(':Default/ShoppingCart/History:list.html.twig', [
            'history'   => $history,
            'income'    => $income,
        ]);
    }
}
