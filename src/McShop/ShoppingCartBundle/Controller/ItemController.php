<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\ShoppingCartBundle\Entity\ShoppingCartItem as Item;
use McShop\ShoppingCartBundle\Form\ShoppingCartItemType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends BaseController
{
    public function listAction(Request $request)
    {
        if (!$this->isGranted('ROLE_ITEM_LIST')) {
            throw $this->createAccessDeniedException();
        }
        
        $this->get('app.title')->setValue('shopping_cart.item.title.list');

        $items = $this->getDoctrine()->getManagerForClass('McShopShoppingCartBundle:ShoppingCartItem')
            ->getRepository('McShopShoppingCartBundle:ShoppingCartItem')
            ->findAllAsPagination();

        $items
            ->setMaxPerPage($request->get('pre_page', 30))
            ->setCurrentPage($request->get('page', 1))
        ;

        return $this->render(':Default/ShoppingCart/Item:list.html.twig', [
            'items' => $items,
        ]);
    }

    public function newAction(Request $request)
    {
        if (!$this->isGranted('ROLE_ITEM_NEW')) {
            throw $this->createAccessDeniedException();
        }
        $this->get('app.title')->setValue('shopping_cart.item.title.new');

        $form = $this->getForm();

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($this->processForm($form, $this->getUploadCallback($request, $form))) {
                $this->addFlash('success', $this->trans('shopping_cart.item.new_success'));

                return $this->redirectToRoute('mc_shop_shopping_cart_manage_item_list');
            }
        }

        return $this->render(':Default/ShoppingCart/Item:item.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    public function editAction(Item $item, Request $request)
    {
        if (!$this->isGranted('ROLE_ITEM_EDIT')) {
            throw $this->createAccessDeniedException();
        }
        $this->get('app.title')->setValue('shopping_cart.item.title.edit');

        $form = $this->getForm($item);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($this->processForm($form, $this->getUploadCallback($request, $form))) {
                $this->addFlash('success', $this->trans('shopping_cart.item.edit_success'));

                return $this->redirectToRoute('mc_shop_shopping_cart_manage_item_list');
            }
        }

        return $this->render(':Default/ShoppingCart/Item:item.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Item $item)
    {
        if (!$this->isGranted('ROLE_ITEM_REMOVE')) {
            throw $this->createAccessDeniedException();
        }

        if ($item->getImage() !== null && file_exists($item->getImage())) {
            unlink($item->getImage());
        }

        $this->getDoctrine()->getManagerForClass(get_class($item))
            ->remove($item);
        $this->getDoctrine()->getManagerForClass(get_class($item))
            ->flush();

        $this->addFlash('info', $this->trans('shopping_cart.item.remove_success'));

        return $this->redirectToRoute('mc_shop_shopping_cart_manage_item_list');
    }

    private function getForm(Item $item = null)
    {
        return $this->createForm(ShoppingCartItemType::class, $item);
    }

    /**
     * @param FormInterface $form
     * @param callable|null $callback
     * @return bool
     */
    private function processForm(FormInterface $form, $callback = null)
    {
        if (!$form->isValid()) {
            $errors = $this->get('validator')->validate($form);
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            return false;
        }

        /** @var Item $item */
        $item = $form->getData();
        if ($callback !== null) {
            $item = $callback($item);
        }

        $this->getDoctrine()->getManagerForClass(get_class($item))->persist($item);
        $this->getDoctrine()->getManagerForClass(get_class($item))->flush();

        return true;
    }

    private function getUploadCallback(Request $request, FormInterface $form)
    {
        $callback = function ($item) use ($request, $form) {
            $file = $request->files->get($form->getName())[$form->get('image')->getName()];
            if ($file instanceof UploadedFile) {
                if ($item->getImage() !== null && file_exists($item->getImage())) {
                    unlink($item->getImage());
                }

                $directory = 'upload/shopping_cart/items/' . $form->get('type')->getData() . '/';
                $randomFileName = uniqid(mt_rand()) . '.' . $file->getClientOriginalExtension();
                $file->move($directory, $randomFileName);

                $item->setImage($directory . $randomFileName);
            }

            return $item;
        };

        return $callback;
    }
}
