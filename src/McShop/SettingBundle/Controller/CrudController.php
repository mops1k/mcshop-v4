<?php
namespace McShop\SettingBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\SettingBundle\Entity\Setting;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Request;

class CrudController extends BaseController
{
    public function indexAction()
    {
        if (!$this->isGranted('ROLE_SETTING_EDIT')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('setting.title');

        $finder = new Finder();
        $directories = $finder->in($this->getParameter('kernel.root_dir') . '/Resources/views/')->directories();

        $templates = [];
        /** @var SplFileInfo $directory */
        foreach ($directories->depth(0) as $directory) {
            $templates[] = $directory->getBasename();
        }

        return $this->render(':Default/Setting:edit.html.twig', [
            'templates'  => $templates
        ]);
    }

    public function saveAction(Request $request)
    {
        if (!$this->isGranted('ROLE_SETTING_EDIT')) {
            throw $this->createAccessDeniedException();
        }

        $updatedSettings = $request->get('setting', []);

        foreach ($updatedSettings as $name => $value) {
            $setting = $this->getDoctrine()
                ->getManagerForClass('McShopSettingBundle:Setting')
                ->getRepository('McShopSettingBundle:Setting')
                ->findOneByName($name)
            ;

            if (!$setting instanceof Setting) {
                $setting = new Setting();
                $setting->setName($name);
            }

            if ($setting->getValue() === $value) {
                continue;
            }

            $setting->setValue($value);
            $this->getDoctrine()->getManagerForClass('McShopSettingBundle:Setting')->persist($setting);
        }

        $this->getDoctrine()->getManagerForClass('McShopSettingBundle:Setting')->flush();

        $this->addFlash('success', $this->trans('setting.save_success'));
        return $this->redirectToRoute('mc_shop_setting_homepage');
    }
}
