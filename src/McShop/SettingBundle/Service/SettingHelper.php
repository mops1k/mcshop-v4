<?php
namespace McShop\SettingBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\SettingBundle\Entity\Setting;

/**
 * Class SettingHelper
 * @package McShop\SettingBundle\Service
 */
class SettingHelper
{
    /** @var ManagerRegistry */
    private $doctrine;
    /** @var array */
    private $loadedSetting = [];

    /**
     * SettingHelper constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get setting by key
     *
     * @param string        $name
     * @param string|null   $defaultValue
     * @param bool          $loadFromCache
     * @return null|string
     */
    public function get($name, $defaultValue = null, $loadFromCache = true)
    {
        if (isset($this->loadedSetting[$name]) && $loadFromCache) {
            return $this->loadedSetting[$name];
        }

        $setting = $this->doctrine->getManagerForClass('McShopSettingBundle:Setting')
            ->getRepository('McShopSettingBundle:Setting')->findOneByName($name);

        $this->loadedSetting[$name] = $setting instanceof Setting ? $setting->getValue() : $defaultValue;

        return $this->loadedSetting[$name];
    }
}
