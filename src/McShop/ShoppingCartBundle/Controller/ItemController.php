<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\ShoppingCartBundle\Entity\ShoppingCartItem as Item;
use McShop\ShoppingCartBundle\Form\ShoppingCartItemType;
use McShop\UserBundle\Controller\BaseController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends BaseController
{
    public function listAction()
    {
        return $this->render('', []);
    }

    public function newAction(Request $request)
    {
        $form = $this->getForm();

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);

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

            if ($this->processForm($form, $callback)) {
                $this->addFlash('success', $this->trans('shopping_cart.item.new_success'));

                return $this->redirectToRoute('mc_shop_shopping_cart_item_list');
            }
        }

        return $this->render(':Default/ShoppingCart/Item:item.html.twig', [
            'form'  => $form->createView(),
        ]);
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
}
