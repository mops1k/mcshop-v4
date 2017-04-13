<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\ShoppingCartBundle\Entity\BuyHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BuyHistoryController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        if (!$this->isGranted('ROLE_HISTORY_LIST')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('shopping_cart.history.menu');

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

    /**
     * @param BuyHistory $history
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(BuyHistory $history)
    {
        if (!$this->isGranted('ROLE_HISTORY_VIEW')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')
            ->setValue('shopping_cart.history.entry')
            ->setAttributes(['@nn@' => $history->getId()])
        ;

        return $this->render(':Default/ShoppingCart/History:view.html.twig', [
            'history' => $history,
        ]);
    }
}
