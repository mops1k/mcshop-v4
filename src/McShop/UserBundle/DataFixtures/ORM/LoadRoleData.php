<?php
namespace McShop\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use McShop\UserBundle\Entity\Role;
use Symfony\Component\Yaml\Yaml;

class LoadRoleData extends AbstractFixture implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = Yaml::parse(file_get_contents(__DIR__ . '/data/role.yml'));

        foreach ($data as $id => $item) {
            $role = $manager->getRepository('McShopUserBundle:Role')->findOneByRole($item['role']);
            if ($role === null) {
                $role = new Role();
            }

            $role
                ->setName($item['name'])
                ->setRole($item['role'])
            ;

            if (isset($item['parent']) && $this->hasReference($item['parent'])) {
                $role->addParent($this->getReference($item['parent']));
            }

            $manager->persist($role);

            $this->setReference($id, $role);
        }

        $manager->flush();
    }
}
