<?php
namespace McShop\MenuBundle\Model\Common;

use McShop\MenuBundle\Model\BuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    /** @var array */
    protected $menu = [];


    /** @var TranslatorInterface */
    protected $translator;

    /**
     * AbstractBuilder constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $name
     * @param string $title
     * @param string $url
     * @param array $options
     * @return mixed
     */
    abstract public function addItem(string $name, string $title, string $url, array $options = []);

    /**
     * @param string $key
     * @param array  $options
     * @param string $domain
     *
     * @return string
     */
    public function trans($key, array $options = [], $domain = 'messages')
    {
        return $this->translator->trans($key, $options, $domain);
    }

    /**
     * @return mixed
     */
    public function getMenu(): array
    {
        return $this->menu;
    }

    public function clearMenu()
    {
        $this->menu = [];

        return $this;
    }
}