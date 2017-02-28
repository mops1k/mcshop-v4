<?php

namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\FinanceBundle\Form\CouponCodeType;
use McShop\UserBundle\Form\PasswordType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    public function indexAction()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $passwordForm = $this->createForm(PasswordType::class, $this->getUser(), [
            'action'    => $this->generateUrl("mc_shop_user_password_change"),
        ]);

        $couponForm = $this->createForm(CouponCodeType::class, null, [
            'action'    => $this->generateUrl("mc_shop_finance_coupon_activation")
        ]);

        return $this->render(':Default/User/Profile:index.html.twig', [
            'passwordForm' => $passwordForm->createView(),
            'couponForm'   => $couponForm->createView(),
        ]);
    }

    public function passwordAction(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $passwordForm = $this->createForm(PasswordType::class, $this->getUser(), []);
        $passwordForm->handleRequest($request);

        if (!$passwordForm->isValid()) {
            $errors = $this->get('validator')->validate($passwordForm);
            foreach ($errors as $error) {
                $this->addFlash('error', $this->get('translator')->trans($error->getMessage()));
            }
            return $this->redirectToRoute('mc_shop_user_profile');
        }

        $userHelper = $this->get('mc_shop.user.helper');
        $userHelper
            ->setUser($this->getUser())
            ->setNewPassword(true)
            ->save($this->getUser()->getActive(), $this->getUser()->getLocked())
        ;

        $this->addFlash('info', $this->get('translator')->trans('user.profile.password.changed'));

        return $this->redirectToRoute('mc_shop_user_profile');
    }

    /**
     * @param $choice
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function skinAsAvatarAction($choice)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        switch ($choice) {
            case 'avatar':
                $user->setSkinAsAvatar(false);
                break;
            case 'head':
                $user->setSkinAsAvatar(true);
                break;
        }

        $this->getDoctrine()->getManagerForClass('McShopUserBundle:User')->persist($user);
        $this->getDoctrine()->getManagerForClass('McShopUserBundle:User')->flush();

        return $this->redirectToRoute('mc_shop_user_profile');
    }

    public function uploadFileAction(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $files = $request->files->all();

        foreach ($files as $name => $file) {
            /** @var UploadedFile $file */
            if ($name === 'avatar') {
                $this->uploadAvatar($file);
                return $this->redirectToRoute('mc_shop_user_profile');
            }

            $type = $request->get('type');

            $allowedTypes = [ IMAGETYPE_PNG ];
            if (!in_array(exif_imagetype($file->getPathname()), $allowedTypes)
                || $file->getClientOriginalExtension() !== 'png') {
                $this->addFlash('error', $this->get('translator')->trans('user.profile.skins.wrong_type'));
                return $this->redirectToRoute('mc_shop_user_profile');
            }

            $filename = $this->getUser()->getUUID() . '.png';

            if ($type == 0) {
                $size = getimagesize($file->getPathname());

                if ($this->getUser()->getHdBuyDate() === null && $size[0] > 64 && $size[1] > 32) {
                    $this->addFlash('error', $this->get('translator')->trans('user.profile.skins.no_hd_rights'));
                    return $this->redirectToRoute('mc_shop_user_profile');
                }

                $directory = 'minecraft/skins/';
                if (file_exists($directory . $filename)) {
                    unlink($directory . $filename);
                }

                if (file_exists('minecraft/head/' . $this->getUser()->getUuid() . '.png')) {
                    unlink('minecraft/head/' . $this->getUser()->getUuid() . '.png');
                }
                unlink('minecraft/preview/' . $this->getUser()->getUuid() . '_front.png');
                unlink('minecraft/preview/' . $this->getUser()->getUuid() . '_back.png');

                $file->move($directory, $filename);
                $this->getUser()->getSkinPreview('front');
                $this->getUser()->getSkinPreview('back');

                return $this->redirectToRoute('mc_shop_user_profile');
            }

            if ($type == 1) {
                if ($this->getUser()->getHdBuyDate() === null) {
                    $this->addFlash('error', $this->get('translator')->trans('user.profile.skins.no_hd_rights'));
                    return $this->redirectToRoute('mc_shop_user_profile');
                }

                $directory = 'minecraft/cloacks/';

                if (file_exists($directory . $filename)) {
                    unlink($directory . $filename);
                }
                unlink('minecraft/preview/' . $this->getUser()->getUuid() . '_back.png');
                $file->move($directory, $filename);
                $this->getUser()->getSkinPreview('back');

                return $this->redirectToRoute('mc_shop_user_profile');
            }
        }

        return $this->redirectToRoute('mc_shop_user_profile');
    }

    /**
     * @param $type
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unlinkAction($type)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $filename = $this->getUser()->getUUID() . '.png';

        if ($type === 'skin') {
            $directory = 'minecraft/skins/';
            if (file_exists($directory . $filename)) {
                unlink($directory . $filename);
            }

            if (file_exists('minecraft/head/' . $this->getUser()->getUuid() . '.png')) {
                unlink('minecraft/head/' . $this->getUser()->getUuid() . '.png');
            }
            unlink('minecraft/preview/' . $this->getUser()->getUuid() . '_front.png');
            unlink('minecraft/preview/' . $this->getUser()->getUuid() . '_back.png');
            $this->getUser()->getSkinPreview('front');
            $this->getUser()->getSkinPreview('back');
        }

        if ($type === 'cloak') {
            $directory = 'minecraft/cloacks/';
            if (file_exists($directory . $filename)) {
                unlink($directory . $filename);
            }

            unlink('minecraft/preview/' . $this->getUser()->getUuid() . '_back.png');
            $this->getUser()->getSkinPreview('back');
        }

        return $this->redirectToRoute('mc_shop_user_profile');
    }

    /**
     * @param UploadedFile $file
     */
    private function uploadAvatar(UploadedFile $file)
    {
        $user = $this->getUser();
        $directory = 'upload/avatar/';
        $filename = md5($this->getUser()->getUsername()) . '.' . $file->getClientOriginalExtension();

        $allowedTypes = [ IMAGETYPE_PNG, IMAGETYPE_JPEG ];
        if (!in_array(exif_imagetype($file->getPathname()), $allowedTypes)
            || !in_array($file->getClientOriginalExtension(), [ 'jpg', 'jpeg', 'png' ])) {
            $this->addFlash('error', $this->get('translator')->trans('user.profile.avatar.wrong_type'));
            return;
        }

        $imagesize = getimagesize($file->getPathname());

        if ($imagesize[0] > 200 || $imagesize[1] > 200) {
            $this->addFlash('error', $this->get('translator')->trans('user.profile.avatar.wrong_size'));
            return;
        }

        $file->move($directory, $filename);
        $user
            ->setAvatar($directory . $filename)
            ->setSkinAsAvatar(false)
        ;

        $this->getDoctrine()->getManagerForClass('McShopUserBundle:User')->persist($user);
        $this->getDoctrine()->getManagerForClass('McShopUserBundle:User')->flush();
    }
}
