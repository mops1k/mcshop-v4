<?php

namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\Core\Twig\Title;
use McShop\UserBundle\Entity\User;
use McShop\UserBundle\Form\UserEditType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ManageController extends BaseController
{
    public function indexAction(Request $request)
    {
        if (!$this->isGranted('ROLE_USERS_LIST')) {
            throw $this->createAccessDeniedException();
        }

        $this->get(Title::class)->setValue('user.manage.list');

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
            ->getManagerForClass(User::class)
            ->getRepository(User::class)
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

        $this->getDoctrine()->getManagerForClass(User::class)->persist($user);
        $this->getDoctrine()->getManagerForClass(User::class)->flush();

        return $this->redirectToReferer();
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(User $user, Request $request)
    {
        foreach ($user->getRoles() as $role) {
            if (!$this->isGranted($role->getRole())) {
                throw $this->createAccessDeniedException('You can not edit this user!');
            }
        }

        $this->get(Title::class)->setValue('user.manage.edit_user')->setAttributes([
            '@username@'    => $user->getUsername(),
        ]);

        $oldPassword = $user->getPassword();
        $form = $this->createForm(UserEditType::class, $user, [
            'current_user'  => $this->getUser()
        ]);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user->setPassword($oldPassword);
                if (!empty($form->get('password')->getData()) && $form->get('password')->getData() !== null) {
                    $password = $this->get('security.password_encoder')
                        ->encodePassword($user, $form->get('password')->getData());
                    $user->setPassword($password);
                }

                $this->getDoctrine()->getManagerForClass(User::class)->persist($user);
                $this->getDoctrine()->getManagerForClass(User::class)->flush();

                $this->addFlash('info', $this->trans('user.manage.edit_success'));

//                return $this->redirectToReferer();
            }

            foreach ($form->getErrors() as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        return $this->render(':Default/User/Manage:edit.html.twig', [
            'user'  => $user,
            'form'  => $form->createView(),
        ]);
    }
}
