<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\ShoppingCartBundle\Entity\ShoppingCartItem as Item;
use McShop\ShoppingCartBundle\Entity\ShoppingCartItem;
use McShop\ShoppingCartBundle\Form\ShoppingCartItemType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ITEM_LIST')) {
            throw $this->createAccessDeniedException();
        }
        
        $this->get('app.title')->setValue('shopping_cart.item.title.list');

        $items = $this->getDoctrine()->getManagerForClass(ShoppingCartItem::class)
            ->getRepository(ShoppingCartItem::class)
            ->findAllAsPagination();

        $items
            ->setMaxPerPage($request->get('pre_page', 30))
            ->setCurrentPage($request->get('page', 1))
        ;

        return $this->render(':Default/ShoppingCart/Item:list.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request): Response
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

    /**
     * @param ShoppingCartItem $item
     * @param Request $request
     * @return Response
     */
    public function editAction(Item $item, Request $request): Response
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
     * @param ShoppingCartItem $item
     * @return Response
     */
    public function removeAction(Item $item): Response
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

    /**
     * @param ShoppingCartItem|null $item
     * @return FormInterface
     */
    private function getForm(Item $item = null): FormInterface
    {
        return $this->createForm(ShoppingCartItemType::class, $item);
    }

    /**
     * @param FormInterface $form
     * @param callable|null $callback
     * @return bool
     */
    private function processForm(FormInterface $form, $callback = null): bool
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

    /**
     * @param Request $request
     * @param FormInterface $form
     * @return \Closure
     */
    private function getUploadCallback(Request $request, FormInterface $form): \Closure
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
