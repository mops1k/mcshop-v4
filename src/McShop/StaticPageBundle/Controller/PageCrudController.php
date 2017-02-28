<?php

namespace McShop\StaticPageBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\StaticPageBundle\Entity\Page;
use McShop\StaticPageBundle\Form\PageType;
use McShop\StaticPageBundle\Repository\PageRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class PageCrudController
 * @package McShop\StaticPageBundle\Controller
 */
class PageCrudController extends BaseController
{
    public function listAction(Request $request)
    {
        if (!$this->isGranted('ROLE_STATIC_PAGE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $query = $this->getDoctrine()
            ->getManagerForClass('McShopStaticPageBundle:Page')
            ->getRepository('McShopStaticPageBundle:Page')
            ->findAll(PageRepository::RETURN_QUERY)
        ;

        $adapter = new DoctrineORMAdapter($query);
        /** @var Page[] $posts */
        $pages = new Pagerfanta($adapter);
        $pages
            ->setMaxPerPage($request->get('per_page', 10))
            ->setCurrentPage($request->get('page', 1))
        ;

        return $this->render(':Default/StaticPage:list.html.twig', [
            'pages' => $pages,
        ]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        if (!$this->isGranted('ROLE_STATIC_PAGE_ADD')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('page.add.title');

        $form = $this->getForm();
        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($this->processForm($form)) {
                $this->addFlash('info', $this->get('translator')->trans('page.add_success'));

                return $this->redirectToRoute('mc_shop_static_page_view', [
                    'slug'      => $form->getData()->getSlug(),
                    '_locale'   => $request->getLocale(),
                ]);
            }
        }

        return $this->render(':Default/StaticPage:page.html.twig', [
            'header_title'  => $this->get('translator')->trans('page.add.title'),
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($slug, Request $request)
    {
        if (!$this->isGranted('ROLE_STATIC_PAGE_EDIT')) {
            throw $this->createAccessDeniedException();
        }

        $page = $this->getDoctrine()
            ->getManagerForClass('McShopStaticPageBundle:Page')
            ->getRepository('McShopStaticPageBundle:Page')
            ->findOneBySlug($slug)
        ;

        if ($page === null) {
            $this->addFlash('error', $this->get('translator')->trans('page.not_found'));

            return $this->redirectToReferer();
        }

        $this->get('app.title')->setValue('page.edit.title');

        $form = $this->getForm($page);
        $form->remove('slug');
        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($this->processForm($form)) {
                $this->addFlash('info', $this->get('translator')->trans('page.edit_success'));

                return $this->redirectToRoute('mc_shop_static_page_view', [
                    'slug'      => $form->getData()->getSlug(),
                    '_locale'   => $request->getLocale(),
                ]);
            }
        }

        return $this->render(':Default/StaticPage:page.html.twig', [
            'header_title'  => $this->get('translator')->trans('page.edit.title'),
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($slug, Request $request)
    {
        if (!$this->isGranted('ROLE_STATIC_PAGE_REMOVE')) {
            throw $this->createAccessDeniedException();
        }

        $page = $this->getDoctrine()
            ->getManagerForClass('McShopStaticPageBundle:Page')
            ->getRepository('McShopStaticPageBundle:Page')
            ->findOneBySlug($slug)
        ;

        if ($page === null) {
            $this->addFlash('error', $this->get('translator')->trans('page.not_found'));

            return $this->redirectToReferer();
        }

        $this->getDoctrine()->getManagerForClass(get_class($page))->remove($page);
        $this->getDoctrine()->getManagerForClass(get_class($page))->flush();

        $this->addFlash('info', $this->get('translator')->trans('page.deleted'));

        return $this->redirectToRoute('homepage', [ '_locale' => $request->getLocale() ]);
    }

    /**
     * @param Page|null $page
     * @return \Symfony\Component\Form\Form
     */
    private function getForm($page = null)
    {
        if ($page !== null && !$page instanceof Page) {
            throw new UnprocessableEntityHttpException('Wrong parameter for $post');
        }

        $form = $this->createForm(PageType::class, $page);
        return $form;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    private function processForm(FormInterface $form)
    {
        if (!$form->isValid()) {
            $errors = $this->get('validator')->validate($form);
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            return false;
        }

        /** @var Page $page */
        $page = $form->getData();
        if ($page->getUser() === null) {
            $page->setUser($this->getUser());
        }

        $this->getDoctrine()->getManagerForClass(get_class($page))->persist($page);
        $this->getDoctrine()->getManagerForClass(get_class($page))->flush();

        return true;
    }
}
