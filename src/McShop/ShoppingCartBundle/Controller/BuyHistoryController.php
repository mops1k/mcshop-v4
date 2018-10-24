<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\Core\Twig\Title;
use McShop\ShoppingCartBundle\Entity\BuyHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BuyHistoryController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        if (!$this->isGranted('ROLE_HISTORY_LIST')) {
            throw $this->createAccessDeniedException();
        }

        $this->get(Title::class)->setValue('shopping_cart.history.menu');

        $history = $this->getDoctrine()->getManagerForClass(BuyHistory::class)
            ->getRepository(BuyHistory::class)->findAllAsPagination();
        $history
            ->setCurrentPage($request->get('page', 1))
            ->setMaxPerPage($request->get('per_page', 50))
        ;

        $income = $this->getDoctrine()->getManagerForClass(BuyHistory::class)
            ->getRepository(BuyHistory::class)->getTotalIncome();

        return $this->render(':Default/ShoppingCart/History:list.html.twig', [
            'history'   => $history,
            'income'    => $income,
        ]);
    }

    /**
     * @param BuyHistory $history
     * @return Response
     */
    public function viewAction(BuyHistory $history): Response
    {
        if (!$this->isGranted('ROLE_HISTORY_VIEW')) {
            throw $this->createAccessDeniedException();
        }

        $this->get(Title::class)
            ->setValue('shopping_cart.history.entry')
            ->setAttributes(['@nn@' => $history->getId()])
        ;

        return $this->render(':Default/ShoppingCart/History:view.html.twig', [
            'history' => $history,
        ]);
    }
}
