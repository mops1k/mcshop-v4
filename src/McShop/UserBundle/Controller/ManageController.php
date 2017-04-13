<?php

namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ManageController extends BaseController
{
    public function indexAction(Request $request)
    {
        if (!$this->isGranted('ROLE_USERS_LIST')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createFormBuilder(null, [
                'csrf_protection'   => false,
                'method'            => 'GET',
            ])
            ->add('username', TextType::class, [
                'required'  => false,
                'attr'      => [
                    'placeholder'   => 'user.manage.username_placeholder'
                ]
            ])
            ->getForm()
            ->handleRequest($request)
        ;

        $users = $this->getDoctrine()
            ->getManagerForClass('McShopUserBundle:User')
            ->getRepository('McShopUserBundle:User')
            ->getAllPaginated($form)
            ->setCurrentPage($request->get('page', 1))
            ->setMaxPerPage($request->get('per_page', 30))
        ;

        return $this->render(':Default/User/Manage:list.html.twig', [
            'users' => $users,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function blockAction(User $user)
    {
        if (!$this->isGranted('ROLE_USERS_BAN')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->getUser() === $user) {
            $this->addFlash('info', $this->trans('user.manage.ban_error'));

            return $this->redirectToReferer();
        }

        $user->setLocked(!$user->getLocked());

        $this->getDoctrine()->getManagerForClass('McShopUserBundle:User')->persist($user);
        $this->getDoctrine()->getManagerForClass('McShopUserBundle:User')->flush();

        return $this->redirectToReferer();
    }
}
