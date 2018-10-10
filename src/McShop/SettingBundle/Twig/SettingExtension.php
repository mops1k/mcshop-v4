<?php
namespace McShop\SettingBundle\Twig;

use McShop\SettingBundle\Service\SettingHelper;

/**
 * Class SettingExtension
 * @package McShop\SettingBundle\Twig
 */
class SettingExtension extends \Twig_Extension implements \Twig_ExtensionInterface
{
    /** @var SettingHelper */
    private $helper;

    /**
     * SettingExtension constructor.
     * @param SettingHelper $helper
     */
    public function __construct(SettingHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('setting', [ $this->helper, 'get' ]),
        ];
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('setting', [ $this->helper, 'get' ]),
        ];
    }
}
