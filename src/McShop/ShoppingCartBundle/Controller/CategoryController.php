<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\ShoppingCartBundle\Entity\ShoppingCartCategory as Category;
use McShop\ShoppingCartBundle\Form\ShoppingCartCategoryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class CategoryController
 *
 * @package McShop\ShoppingCartBundle\Controller
 */
class CategoryController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        if (!$this->isGranted('ROLE_CATEGORY_LIST')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('shopping_cart.category.list_title');

        $categories = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:ShoppingCartCategory')
            ->getRepository('McShopShoppingCartBundle:ShoppingCartCategory')
            ->findAllWithPagination()
            ->setMaxPerPage($request->get('per_page', 30))
            ->setCurrentPage($request->get('page', 1))
        ;

        return $this->render(':Default/ShoppingCart/Category:list.html.twig', [
            'categories'    => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        if (!$this->isGranted('ROLE_CATEGORY_NEW')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('shopping_cart.category.new_title');

        $form = $this->getForm();

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($this->processForm($form)) {
                $this->addFlash('info', $this->get('translator')->trans('shopping_cart.category.new_success'));

                return $this->redirectToRoute('mc_shop_shopping_cart_manage_category_list');
            }
        }

        return $this->render(':Default/ShoppingCart/Category:category.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Category $category, Request $request)
    {
        if (!$this->isGranted('ROLE_CATEGORY_EDIT')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('shopping_cart.category.edit_title');

        $form = $this->getForm($category);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($this->processForm($form)) {
                $this->addFlash('info', $this->get('translator')->trans('shopping_cart.category.edit_success'));

                return $this->redirectToRoute('mc_shop_shopping_cart_manage_category_list');
            }
        }

        return $this->render(':Default/ShoppingCart/Category:category.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Category $category)
    {
        if (!$this->isGranted('ROLE_CATEGORY_REMOVE')) {
            throw $this->createAccessDeniedException();
        }

        foreach ($category->getItems() as $item) {
            $item->setCategory(null);
            $this->getDoctrine()->getManagerForClass(get_class($category))->remove($item);
        }

        $this->getDoctrine()->getManagerForClass(get_class($category))->remove($category);
        $this->getDoctrine()->getManagerForClass(get_class($category))->flush();

        $this->addFlash('success', $this->get('translator')->trans('shopping_cart.category.delete_success'));

        return $this->redirectToRoute('mc_shop_shopping_cart_manage_category_list');
    }

    /**
     * @param Category|null $category
     * @return \Symfony\Component\Form\Form
     */
    private function getForm($category = null)
    {
        if ($category !== null && !$category instanceof Category) {
            throw new UnprocessableEntityHttpException('Wrong parameter for $category');
        }

        $form = $this->createForm(ShoppingCartCategoryType::class, $category);
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

        /** @var Category $category */
        $category = $form->getData();

        $this->getDoctrine()->getManagerForClass(get_class($category))->persist($category);
        $this->getDoctrine()->getManagerForClass(get_class($category))->flush();

        return true;
    }
}
