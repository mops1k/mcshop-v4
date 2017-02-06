<?php

namespace McShop\UserBundle\Controller;

use Minecraft\SkinView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    public function indexAction()
    {
        return $this->render(':Default/User/Profile:index.html.twig', []);
    }

    public function uploadFileAction(Request $request)
    {
        $files = $request->files->all();

        foreach ($files as $name => $file) {
            /** @var UploadedFile $file */
            if ($name === 'avatar') {
                $this->uploadAvatar($file);
                return $this->redirectToRoute('mc_shop_user_profile');
            }

            $type = $request->get('type');
            if (!in_array($file->getClientMimeType(), ['image/png'])) {
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
                unlink($directory . $filename);
                unlink('minecraft/head/' . $this->getUser()->getUuid() . '.png');
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

                $directory = 'minecraft/cloak/';

                $file->move($directory, $filename);
                unlink($directory . $filename);
                unlink('minecraft/preview/' . $this->getUser()->getUuid() . '_back.png');
                $this->getUser()->getSkinPreview('back');

                return $this->redirectToRoute('mc_shop_user_profile');
            }
        }

        return $this->redirectToRoute('mc_shop_user_profile');
    }

    /**
     * @param UploadedFile $file
     */
    private function uploadAvatar(UploadedFile $file) {
        $user = $this->getUser();
        $directory = 'upload/avatar/';
        $filename = md5($this->getUser()->getUsername()) . '.' . $file->getClientOriginalExtension();
        if (!in_array($file->getClientMimeType(), ['image/png', 'image/jpeg'])) {
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
