<?php
namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\Core\Twig\Title;
use McShop\UserBundle\Entity\User;
use McShop\UserBundle\Form\UserLoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function loginAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
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

        $this->get(Title::class)->setValue('title.authorization');

        $form = $this->createForm(UserLoginType::class);
        $form->handleRequest($request);

        if ($form->getErrors()) {
            foreach ($form->getErrors() as $error) {
                $this->addFlash(
                    'error',
                    $this->get('translator')->trans($error->getMessage(), $error->getMessageParameters(), 'security')
                );
            }
        }

        return $this->render(':Default/User:login.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    /**
     * @param $username
     * @param $password
     * @return Response
     */
    public function minecraftLoginAction(string $username, string $password)
    {
        $user = $this->getDoctrine()
            ->getManagerForClass(User::class)
            ->getRepository(User::class)
            ->loadUserByUsername($username);

        if (!$user
            || !$this->get('security.password_encoder')->isPasswordValid($user, $password)
            || $user->getLocked()
            || !$user->getActive()) {
            return new Response($this->get('translator')->trans('user.launcher.login.error'));
        }

        $preResponse = $this->getSetting('launcher_response', 'OK:%username%');

        return new Response(str_replace(
            ['%username%', '%UUID%'],
            [$username, $user->getUUID()],
            $preResponse
        ));
    }
}
