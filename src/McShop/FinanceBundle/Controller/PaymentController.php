<?php

namespace McShop\FinanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
