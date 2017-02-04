<?php

namespace McShop\FinanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

    public function interractionAction(Request $request)
    {
        dump($request);
        return;
    }
}
