<?php
namespace McShop\ShoppingCartBundle\Controller;

use McShop\ShoppingCartBundle\Entity\ShoppingCartItem as Item;
use McShop\ShoppingCartBundle\Form\ShoppingCartItemType;
use McShop\UserBundle\Controller\BaseController;
use Symfony\Component\Form\FormInterface;
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
            if ($this->processForm($form)) {
                $this->addFlash('success', $this->trans('shopping_cart.item.new_success'));

                return $this->redirectToRoute();
            }
        }

        return $this->render('', [
            'form'  => $form->createView(),
        ]);
    }

    private function getForm(Item $item = null)
    {
        return $this->createForm(ShoppingCartItemType::class, $item);
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

        /** @var Item $item */
        $item = $form->getData();

        $this->getDoctrine()->getManagerForClass(get_class($item))->persist($item);
        $this->getDoctrine()->getManagerForClass(get_class($item))->flush();

        return true;
    }
}
