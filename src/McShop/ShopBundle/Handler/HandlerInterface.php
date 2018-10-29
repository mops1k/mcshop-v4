<?php declare(strict_types=1);

namespace McShop\ShopBundle\Handler;

use McShop\ShopBundle\Entity\Item;
use Symfony\Component\Form\FormInterface;

interface HandlerInterface
{
    public function getName(): string;
    public function parseExtra(): void;
    public function getExtraField(string $fieldName);
    public function setItem(Item $item): HandlerInterface;
    public function getFormType(): string;
}
