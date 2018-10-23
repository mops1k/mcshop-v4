<?php
namespace McShop\SettingBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\Core\Twig\Title;
use McShop\SettingBundle\Entity\Setting;
use McShop\SettingBundle\Form\SettingsType;
use McShop\SettingBundle\Service\SettingHelper;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(): Response
    {
        if (!$this->isGranted('ROLE_SETTING_EDIT')) {
            throw $this->createAccessDeniedException();
        }

        $this->get(Title::class)->setValue('setting.title');

        $finder = new Finder();
        $directories = $finder->in($this->getParameter('kernel.root_dir') . '/Resources/views/')->directories();

        $templates = [];
        /** @var SplFileInfo $directory */
        foreach ($directories->depth(0) as $directory) {
            $templates[$directory->getBasename()] = $directory->getBasename();
        }

        $form = $this->createForm(SettingsType::class, null, [
            'templates' => $templates,
            'settings' => $this->get(SettingHelper::class),
        ]);

        return $this->render(':Default/Setting:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request): Response
    {
        if (!$this->isGranted('ROLE_SETTING_EDIT')) {
            throw $this->createAccessDeniedException();
        }

        $updatedSettings = $request->get('setting', []);

        foreach ($updatedSettings as $name => $value) {
            $setting = $this->getDoctrine()
                ->getManagerForClass(Setting::class)
                ->getRepository(Setting::class)
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
            $this->getDoctrine()->getManagerForClass(Setting::class)->persist($setting);
        }

        $this->getDoctrine()->getManagerForClass(Setting::class)->flush();

        $this->addFlash('success', $this->trans('setting.save_success'));
        return $this->redirectToRoute('mc_shop_setting_homepage');
    }
}
