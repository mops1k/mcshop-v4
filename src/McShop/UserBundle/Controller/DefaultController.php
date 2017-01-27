<?php

namespace McShop\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function loginAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash(
                'info',
                $this->get('translator')->trans('login.error.already_logged_in')
            );
            return $this->redirectToReferer();
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash(
                'error',
                $this->get('translator')->trans($error->getMessageKey(), $error->getMessageData(), 'security')
            );
        }
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(':Default/User:login.html.twig', [
            'last_username'  => $lastUsername
        ]);
    }

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
