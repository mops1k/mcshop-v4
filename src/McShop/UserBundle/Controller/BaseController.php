<?php
namespace McShop\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * Redirect to referer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToReferer()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $url = $request->headers->get('referer') === null ?
            $this->generateUrl('homepage', [ '_locale' => $request->get('_locale')]) : $request->headers->get('referer')
        ;
        return $this->redirect($url);
    }
}