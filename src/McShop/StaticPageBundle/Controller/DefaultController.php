<?php

namespace McShop\StaticPageBundle\Controller;

use McShop\Core\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

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
            $this->get('app.title')->setValue('page.error')->setAttributes([
                '@number@'   => Response::HTTP_NOT_FOUND,
            ]);
            $response = $this->render(':Default/StaticPage:view.html.twig', [
                'title'     => $this->get('translator')->trans('page.error', [
                    '@number@'   => Response::HTTP_NOT_FOUND,
                ]),
                'content'   => $this->get('translator')->trans('page.not_found'),
            ]);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }

        if ($page->getRole() !== null
            && (!$this->isGranted($page->getRole()->getRole()) || !$this->isGranted('ROLE_SUPER_ADMIN'))
        ) {
            $this->get('app.title')->setValue('page.error')->setAttributes([
                '@number@'   => Response::HTTP_FORBIDDEN,
            ]);
            $response = $this->render(':Default/StaticPage:view.html.twig', [
                'title'     => $this->get('translator')->trans('page.error', [
                    '@number@'   => Response::HTTP_FORBIDDEN,
                ]),
                'content'   => $this->get('translator')->trans('page.no_rights'),
            ]);
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            return $response;
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
