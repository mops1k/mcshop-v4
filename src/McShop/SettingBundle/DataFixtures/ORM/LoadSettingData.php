<?php
namespace McShop\SettingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use McShop\SettingBundle\Entity\Setting;
use Symfony\Component\Yaml\Yaml;

class LoadSettingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = Yaml::parse(file_get_contents(__DIR__ . '/data/setting.yml'));

        foreach ($data as $name => $value) {
            $setting = $manager->getRepository('McShopSettingBundle:Setting')->findOneByName($name);

            if ($setting instanceof Setting && $setting->getValue() == $value) {
                    continue;
            }

            if ($setting === null) {
                $setting = new Setting();
                $setting->setName($name);
            }

            $setting->setValue($value);

            $manager->persist($setting);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}