<?php
namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\UserBundle\Entity\Token;
use McShop\UserBundle\Form\RecoverType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Class RecoverController
 * @package McShop\UserBundle\Controller
 */
class RecoverController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function recoverAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->isAuthenticatedErrorShow();
        }
        $this->get('app.title')->setValue('user.recover.message.title');

        $form = $this->createForm(RecoverType::class);

        $form->handleRequest($request);
        if ($request->isMethod($request::METHOD_POST)) {
            if (!$form->isValid()) {
                $errors = $this->get('validator')->validate($form);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->render(':Default/User:recover_username.html.twig', [
                    'form'  => $form->createView(),
                ]);
            }

            $data = $form->getData();

            $user = $this->getDoctrine()
                ->getManagerForClass('McShopUserBundle:User')
                ->getRepository('McShopUserBundle:User')
                ->loadUserByUsername($data['user'])
            ;

            if ($user === null) {
                $this->addFlash('error', $this->get('translator')->trans('user.recover.user_not_found'));

                return $this->render(':Default/User:recover_username.html.twig', [
                    'form'  => $form->createView(),
                ]);
            }

            $userHelper = $this->get('mc_shop.user.helper');
            $userHelper
                ->setUser($user)
                ->generateToken(Token::KIND_RECOVER)
            ;

            $userEmailHelper = $this->get('mc_shop.user_email.helper');
            $userEmailHelper->setUser($user)->send(
                $this->get('translator')->trans('user.recover.email.code'),
                $this->getParameter('mailer_from'),
                ':Default/User/Email:recover.html.twig',
                [
                    'code'    => $userHelper->getToken()->getValue(),
                    'code_link' => $this->generateUrl('mc_shop_user_recover_code_check', [
                        'code'      => $userHelper->getToken()->getValue(),
                        '_locale'   => $request->getLocale(),
                    ], UrlGenerator::ABSOLUTE_URL),
                    'enter_code_link' => $this->generateUrl('mc_shop_user_recover_code', [
                        '_locale'   => $request->getLocale(),
                    ], UrlGenerator::ABSOLUTE_URL),
                ]
            );

            return $this->redirectToRoute('mc_shop_user_recover_code');
        }

        return $this->render(':Default/User:recover_username.html.twig', [
            'form'  => $form->createView(),
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

        return $this->render(':Default/User:recover_code.html.twig');
    }

    /**
     * @param $code
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function codeCheckAction($code, Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->isAuthenticatedErrorShow();
        }
        $this->get('app.title')->setValue('user.recover.message.password_title');

        $token = $this->getDoctrine()
            ->getManagerForClass('McShopUserBundle:Token')
            ->getRepository('McShopUserBundle:Token')
            ->findTokenByValue($code)
        ;

        if ($token === null || !$token) {
            return $this->redirectToRoute('mc_shop_user_recover_code', ['_locale'=>$request->getLocale()]);
        }

        $form = $this->createFormBuilder()->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'validation.password.must_match',
            'first_options' => ['label' => 'form.recover.password'],
            'second_options' => ['label' => 'form.recover.re_password'],
        ])->getForm();

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if (!$form->isValid()) {
                $errors = $this->get('validator')->validate($form);

                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute('mc_shop_user_recover_code_check', [
                    'code'      => $code,
                    '_locale'   => $request->getLocale(),
                ]);
            }

            $user = $token->getUser();
            $user->setPassword($form->getData()['password']);
            $token->setActive(false);

            $userHelper = $this->get('mc_shop.user.helper');
            $userHelper
                ->setUser($user)
                ->setNewPassword(true)
                ->save(true, false)
                ->authUser()
            ;

            $this->addFlash('info', $this->get('translator')->trans('user.recover.message.success'));

            return $this->redirectToRoute('homepage', [ '_locale'=>$request->getLocale() ]);
        }

        return $this->render(':Default/User:recover_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
