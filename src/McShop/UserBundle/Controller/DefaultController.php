<?php
namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{
    public function loginAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->isAuthenticatedErrorShow();
        }
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

    public function minecraftLoginAction($username, $password)
    {
        $user = $this->getDoctrine()
            ->getManagerForClass('McShopUserBundle:User')
            ->getRepository('McShopUserBundle:User')
            ->loadUserByUsername($username);

        if ($user === null || !$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            return new Response($this->get('translator')->trans('user.launcher.login.error'));
        }

        return new Response('OK:'.$username);
    }
}
