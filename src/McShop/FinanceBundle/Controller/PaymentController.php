<?php

namespace McShop\FinanceBundle\Controller;

use McShop\FinanceBundle\Entity\Transactions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * @param $amount
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendFormAction($amount)
    {
        return $this->redirect($this->get('mc_shop_finance.payment')->payForm($amount));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function interractionAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManagerForClass('McShopFinanceBundle:Transactions');
        $transaction = $manager->getRepository('McShopFinanceBundle:Transactions')->find($request->get('ik_pm_no'));

        if ($transaction === null || $transaction->getStatus() !== Transactions::STATUS_IN_PROCCESS) {
            if ($transaction != null) {
                $transaction->setStatus(Transactions::STATUS_FAILURE);

                $manager->persist($transaction);
                $manager->flush();
            }
            return new Response('Bad transaction', Response::HTTP_FORBIDDEN);
        }

        if ($this->get('mc_shop_finance.payment')->ikSign($request->request->all())) {

            $purse = $transaction->getPurse();
            $purse->increaseRealCash($transaction->getCash());
            $transaction->setStatus(Transactions::STATUS_SUCCESS);

            $manager->persist($purse);
            $manager->persist($transaction);
            $manager->flush();

            return new Response('Ok');
        }

        $transaction->setStatus(Transactions::STATUS_FAILURE);

        $manager->persist($transaction);
        $manager->flush();

        return new Response('Bad sign', Response::HTTP_FORBIDDEN);
    }
}
