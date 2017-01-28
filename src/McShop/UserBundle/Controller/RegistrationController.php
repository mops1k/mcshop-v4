<?php
namespace McShop\UserBundle\Controller;

use McShop\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;

class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash(
                'info',
                $this->get('translator')->trans('login.error.already_logged_in')
            );
            return $this->redirectToReferer();
        }

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
        }

        return $this->render(':Default/User:registration.html.twig', [
            'form'    => $form->createView(),
        ]);
    }
}
