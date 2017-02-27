<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\ShoppingCartBundle\Entity\Basket;
use McShop\ShoppingCartBundle\Entity\ShoppingCart;
use McShop\ShoppingCartBundle\Entity\ShoppingCartCategory;
use McShop\ShoppingCartBundle\Entity\ShoppingCartItem;
use McShop\ShoppingCartBundle\Form\StorefrontFilterType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShopController
 * @package McShop\ShoppingCartBundle\Controller
 */
class ShopController extends BaseController
{
    /**
     * @param ShoppingCartCategory|null $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storefrontAction(ShoppingCartCategory $category = null, Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('shopping_cart.storefront.home');

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

    /**
     * @param ShoppingCartItem $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToBasketAction(ShoppingCartItem $item)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:Basket');
        $repository = $manager->getRepository('McShopShoppingCartBundle:Basket');

        $basket = $repository->findOneBy([
            'item'  => $item,
            'user'  => $this->getUser(),
        ]);

        if ($basket === null) {
            $basket = new Basket();
            $basket
                ->setItem($item)
                ->setUser($this->getUser())
                ->setAmount(0)
            ;
        }

        $basket->setAmount($basket->getAmount() + 1);
        $manager->persist($basket);
        $manager->flush();

        $this->addFlash('info', $this->trans('shopping_cart.basket.add_success'));

        return $this->redirectToReferer();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function basketAction()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('shopping_cart.basket.menu');

        $manager = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:Basket');
        $repository = $manager->getRepository('McShopShoppingCartBundle:Basket');

        $basket = $repository->findByUser($this->getUser());

        return $this->render(':Default/ShoppingCart/Shop:basket.html.twig', [
            'basket'    => $basket,
        ]);
    }

    /**
     * @param Basket $basket
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function basketRemoveAction(Basket $basket)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:Basket');
        $manager->remove($basket);
        $manager->flush();

        $this->addFlash('info', $this->trans('shopping_cart.basket.remove_success'));

        return $this->redirectToReferer();
    }

    /**
     * @param Basket $basket
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeBasketAmountAction(Basket $basket, Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $amount = $request->get('amount');
        if ($amount < 0) {
            $this->addFlash('error', $this->trans('shopping_cart.basket.wrong_amount'));

            return $this->redirectToReferer();
        }

        if ($amount == 0) {
            return $this->redirectToRoute('mc_shop_shopping_cart_basket_remove', [ 'id' => $basket->getId() ]);
        }

        $basket->setAmount($amount);

        $manager = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:Basket');
        $manager->persist($basket);
        $manager->flush();

        $this->addFlash('info', $this->trans('shopping_cart.basket.changed_amount'));

        return $this->redirectToReferer();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function buyAction()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:Basket');
        $repository = $manager->getRepository('McShopShoppingCartBundle:Basket');

        /** @var Basket[] $baskets */
        $baskets = $repository->findByUser($this->getUser());

        $total = 0;
        foreach ($baskets as $basket) {
            $price = $basket->getItem()->getPrice()-$basket->getItem()->getPrice()/100*$basket->getItem()->getSale();
            $total += $price*$basket->getAmount();
        }

        if ($this->getUser()->getPurse() === null || $this->getUser()->getPurse()->getRealCash() < $total) {
            $this->addFlash('info', $this->trans('shopping_cart.basket.no_money'));

            return $this->redirectToReferer();
        }

        $purse = $this->getUser()->getPurse();
        $purse->setRealCash($purse->getRealCash() - $total);

        $manager->persist($purse);

        foreach ($baskets as $basket) {
            $shoppingCart = new ShoppingCart();
            $shoppingCart
                ->setAmount($basket->getItem()->getAmount() * $basket->getAmount())
                ->setItem($basket->getItem()->getItem())
                ->setType($basket->getItem()->getType())
                ->setExtra($basket->getItem()->getExtra())
                ->setServer($basket->getItem()->getServer()->getShoppingCartId())
                ->setPlayer($basket->getUser()->getUsername())
            ;

            $manager->persist($shoppingCart);
            $manager->remove($basket);
        }

        $manager->flush();

        $this->addFlash('info', $this->trans('shopping_cart.basket.buy_success'));
        return $this->redirectToReferer();
    }
}
