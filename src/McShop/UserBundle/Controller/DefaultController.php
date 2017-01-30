<?php
namespace McShop\UserBundle\Controller;

class DefaultController extends BaseController
{
    public function loginAction()
    {
        $this->isAuthenticatedErrorShow();
        $this->get('app.title')->setValue('title.authorization');

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
}
