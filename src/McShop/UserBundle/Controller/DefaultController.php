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

        $this->get(Title::class)->setValue('title.authorization');

        $form = $this->createForm(UserLoginType::class);
        $form->handleRequest($request);

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
