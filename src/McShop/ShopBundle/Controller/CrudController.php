<?php

namespace McShop\ShopBundle\Controller;

use McShop\Core\Twig\Title;
use McShop\ShopBundle\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CrudController extends Controller
{
    public function indexAction()
    {
        if (!$this->isGranted('ROLE_ITEM_NEW')) {
            throw $this->createAccessDeniedException();
        }
        $this->get(Title::class)->setValue('shop.item.title.new');

        $form = $this->createForm(ItemType::class);

        return $this->render(':Default/Shop:item.html.twig', [
            'form'  => $form->createView(),
        ]);
    }
}
