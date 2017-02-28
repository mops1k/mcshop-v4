<?php
namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class RegistrationController
 * @package McShop\UserBundle\Controller
 */
class RegistrationController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->isAuthenticatedErrorShow();
        }
        $this->get('app.title')->setValue('title.registration_form');

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($request->isMethod($request::METHOD_POST)) {
            if (!$form->isValid()) {
                /** @var ConstraintViolation[] $errors */
                $errors = $this->get('validator')->validate($form);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->render(':Default/User:registration.html.twig', [
                    'form'    => $form->createView(),
                    '_locale' => $request->getLocale(),
                ]);
            }

            $user = $form->getData();
            $userHelper = $this->get('mc_shop.user.helper');
            $userHelper
                ->setRoleName('ROLE_USER')
                ->setNewPassword(true)
                ->setUser($user)
                ->save()
                ->generateToken()
            ;

            $userEmailHelper = $this->get('mc_shop.user_email.helper');
            $userEmailHelper
                ->setUser($userHelper->getToken()->getUser())
                ->send(
                    $this->get('translator')->trans('registration.message.email.approve_code'),
                    $this->getParameter('mailer_from'),
                    ':Default/User/Email:registration.html.twig',
                    [
                        'code'            => $userHelper->getToken()->getValue(),
                        'code_link'       => $this->generateUrl('mc_shop_user_registration_code_check', [
                            'code'    => $userHelper->getToken()->getValue(),
                            '_locale' => $request->getLocale(),
                        ], UrlGeneratorInterface::ABSOLUTE_URL),
                        'enter_code_link' => $this->generateUrl('mc_shop_user_registration_code', [
                            '_locale' => $request->getLocale(),
                        ])
                    ]
                )
            ;
            $this->addFlash('info', $this->get('translator')->trans('registration.message.approve_email'));

            return $this->redirectToRoute('mc_shop_user_registration_code');
        }

        return $this->render(':Default/User:registration.html.twig', [
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function codeAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->isAuthenticatedErrorShow();
        }
        $this->get('app.title')->setValue('title.code_activation');
        return $this->render(':Default/User:registration_code.html.twig');
    }

    /**
     * @param $code
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function codeCheckAction($code, Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->isAuthenticatedErrorShow();
        }
        $token = $this->getDoctrine()
            ->getManagerForClass('McShopUserBundle:Token')
            ->getRepository('McShopUserBundle:Token')
            ->findTokenByValue($code)
        ;

        if ($token === null || !$token) {
            $this->addFlash('error', $this->get('translator')->trans('registration.message.wrong_code'));
            return $this->redirectToRoute('mc_shop_user_registration_code');
        }

        if (!$this->get($token->getHandlerName())->handle($token)) {
            $this->addFlash('error', $this->get('translator')->trans('system.message.unknown_error'));
        }

        return $this->redirectToRoute('homepage', [ '_locale' => $request->getLocale() ]);
    }
}
