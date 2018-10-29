<?php declare(strict_types=1);

namespace McShop\ShopBundle\Handler;

interface HandlerInterface
{
    public function getName(): string;
    public function parseExtra(): void;
    public function getExtraField(string $fieldName);
    public function handle(): void;
}
