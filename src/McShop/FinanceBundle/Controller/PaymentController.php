<?php

namespace McShop\FinanceBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\FinanceBundle\Entity\Transactions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends BaseController
{
    /**
     * @param $amount
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendFormAction($amount)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        return $this->redirect($this->get('mc_shop_finance.payment')->payForm($amount));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function interractionAction(Request $request)
    {
        $ip_stack = array(
            'ip_begin' => '151.80.190.97',
            'ip_end' => '151.80.190.104'
        );

        if (!(ip2long($request->getClientIp()) >= ip2long($ip_stack['ip_begin'])
            && ip2long($request->getClientIp()) <= ip2long($ip_stack['ip_end']))) {
            return new Response('Bad ip', Response::HTTP_FORBIDDEN);
        }

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

        if ($request->get('ik_inv_st') === 'success'
            && $this->get('mc_shop_finance.payment')->ikSign($request->request->all())) {

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

    public function failAction(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->getDoctrine()->getManagerForClass('McShopFinanceBundle:Transactions');
        $transaction = $manager->getRepository('McShopFinanceBundle:Transactions')->find($request->get('ik_pm_no'));

        if ($transaction != null) {
            $transaction->setStatus(Transactions::STATUS_FAILURE);

            $manager->persist($transaction);
            $manager->flush();
        }

        return $this->redirectToRoute('homepage');
    }
}
