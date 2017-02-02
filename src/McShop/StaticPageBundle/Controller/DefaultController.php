<?php

namespace McShop\StaticPageBundle\Controller;

use McShop\UserBundle\Controller\BaseController;

class DefaultController extends BaseController
{
    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug)
    {
        $manager = $this->getDoctrine()->getManagerForClass('McShopStaticPageBundle:Page');
        $page = $manager->getRepository('McShopStaticPageBundle:Page')->findOneBySlug($slug);

        if ($page === null) {
            $this->addFlash('error', $this->get('translator')->trans('page.not_found'));

            return $this->redirectToReferer();
        }


        if ($page->getRole() !== null
            && (!$this->isGranted($page->getRole()->getRole()) || !$this->isGranted('ROLE_SUPER_ADMIN'))
        ) {
            $this->get('app.title')->setValue($this->get('translator')->trans('page.error'));
            return $this->render(':Default/StaticPage:view.html.twig', [
                'title'     => $this->get('translator')->trans('page.error'),
                'content'   => $this->get('translator')->trans('page.no_rights'),
            ]);
        }

        $this->get('app.title')->setValue($page->getTitle());

        $twig = clone $this->get('twig');
        $twig->setLoader(new \Twig_Loader_String());
        $content = $twig->render($page->getContent());

        return $this->render(':Default/StaticPage:view.html.twig', [
            'title'     => $page->getTitle(),
            'content'   => $content,
        ]);
    }
}
