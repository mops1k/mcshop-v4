<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\ShoppingCartBundle\Entity\ShoppingCartCategory;
use McShop\ShoppingCartBundle\Form\StorefrontFilterType;
use McShop\UserBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends BaseController
{
    public function storefrontAction(ShoppingCartCategory $category = null, Request $request)
    {
        $repository = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:ShoppingCartCategory')
            ->getRepository('McShopShoppingCartBundle:ShoppingCartCategory');

        $breadcrumbs = [];
        if ($category !== null) {
            $breadcrumbs = $repository->getPath($category);
        }

        $childrenCategories = [];
        if ($category !== null) {
            $childrenCategories = $repository->getChildren($category);

            // Текущая категория и подкатегории для запроса товаров из БД
            $childrenCategories[] = $category;
        }

        $filterForm = $this->createForm(StorefrontFilterType::class);
        $filterForm->handleRequest($request);

        $items = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:ShoppingCartItem')
            ->getRepository('McShopShoppingCartBundle:ShoppingCartItem')
            ->findAllAsPagination($childrenCategories, $filterForm)
            ->setMaxPerPage($request->get('per_page', 16))
            ->setCurrentPage($request->get('page', 1))
        ;

        if ($category === null) {
            $childrenCategories = $repository->getRootNodes();
        } else {
            $childrenCategories = $category->getChildren();
        }


        return $this->render(':Default/ShoppingCart/Shop:storefront.html.twig', [
            'items'         => $items,
            'breadcrumbs'   => $breadcrumbs,
            'categories'    => $childrenCategories,
            'filter'        => $filterForm->createView(),
        ]);
    }

    public function basketAction(Request $request)
    {
        //
    }
}
