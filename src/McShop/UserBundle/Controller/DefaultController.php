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

        $session = $this->get('session');
        $attemtpsCache = json_decode($session->get('login_attempts', '{"attempts":0}'), true);

        $access = true;
        ++$attemtpsCache['attempts'];
        $previousAttempt = new \DateTime();
        if ($attemtpsCache['attempts'] >= $this->getParameter('login')['max_attempts']) {
            if (isset($attemtpsCache['last_attempt'])) {
                $previousAttempt = new \DateTime($attemtpsCache['last_attempt']);
            }
            $previousAttempt->add(
                \DateInterval::createFromDateString($this->getParameter('login')['interval'] . ' minutes')
            );
            $currentAttempt = new \DateTime();
            if ($currentAttempt > $previousAttempt) {
                $attemtpsCache['attempts'] = 0;
                unset($attemtpsCache['last_attempt']);
            } else {
                $attemtpsCache['last_attempt'] = isset($attemtpsCache['last_attempt']) ?
                    $attemtpsCache['last_attempt'] : $currentAttempt->format('d.m.Y H:i:s');
                $access = false;
            }
        }
        $session->set('login_attempts', json_encode($attemtpsCache));

        if (!$access) {
            throw new \Exception($this->trans('system.message.max_attempts', [
                '@interval@'  => $this->getParameter('login')['interval'],
                '@next_try@'  => $previousAttempt->format('d.m.Y H:i:s')
            ]));
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

        if ($user === null
            || !$this->get('security.password_encoder')->isPasswordValid($user, $password)
            || $user->getLocked()
            || !$user->getActive()) {
            return new Response($this->get('translator')->trans('user.launcher.login.error'));
        }

        return new Response('OK:'.$username);
    }
}
