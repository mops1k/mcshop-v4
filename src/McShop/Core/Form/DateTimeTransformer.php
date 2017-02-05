<?php
namespace McShop\Core\Form;

use Symfony\Component\Form\FormInterface;

class DateTimeTransformer extends TextTransformer
{
    protected function defaultValueFrom(FormInterface $form)
    {
        return $form->getViewData();
    }
}
