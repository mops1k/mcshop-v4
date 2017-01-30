<?php
namespace McShop\UserBundle\Controller;

use McShop\UserBundle\Entity\Token;
use McShop\UserBundle\Form\RecoverType;
use Symfony\Component\HttpFoundation\Request;

class RecoverController extends BaseController
{
    public function recoverAction(Request $request)
    {
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

            if (!$user) {
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
                ]
            );

            return $this->redirectToRoute('mc_shop_user_registration_code');
        }

        return $this->render(':Default/User:recover_username.html.twig', [
            'form'  => $form->createView(),
        ]);
    }
}
