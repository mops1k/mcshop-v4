<?php
namespace McShop\Twig;


class Title
{
    /** @var string */
    private $value = null;

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}