<?php
namespace McShop\SettingBundle\Controller;

use McShop\Core\Controller\BaseController;

class CrudController extends BaseController
{
    public function indexAction()
    {
        return $this->render(':Default:base.html.twig');
    }
}
