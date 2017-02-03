<?php
namespace McShop\UserBundle\Services;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use McShop\UserBundle\Entity\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy as RoleHierarchyStandart;

class RoleHierarchy extends RoleHierarchyStandart
{
    /** @var EntityManager em */
    private $em;
    private $hierarchy;

    /**
     * @param array $hierarchy
     */
    public function __construct(array $hierarchy, ManagerRegistry $em)
    {
        $this->em = $em->getManagerForClass("McShopUserBundle:Role");
        $this->hierarchy = $hierarchy;

        parent::__construct($this->getRoles());
    }


    /**
     * Here we build an array with roles. It looks like a two-levelled tree - just
     * like original Symfony roles are stored in security.yml
     * @return array
     */
    private function buildRolesTree()
    {
        if (php_sapi_name() === 'cli') {
            return [];
        }

        $hierarchy = $this->hierarchy;
        $qb = $this->em->createQueryBuilder();
        $qb->select('r,p,c')
            ->from('McShopUserBundle:Role', 'r')
            ->leftJoin('r.parents', 'p')
            ->leftJoin('r.childrens', 'c')
        ;
        $query = $qb->getQuery();
        $query->useResultCache(true, 600);
        $query->useQueryCache(true);

        $roles = $query->getResult();

        foreach ($roles as $role) {
            /** @var Role $role */
            $hierarchy[$role->getRole()] = [];

            foreach ($role->getChildrens() as $childrenRole) {
                if (!in_array($childrenRole->getRole(), $hierarchy[$role->getRole()])) {
                    $hierarchy[$role->getRole()][] = $childrenRole->getRole();
                }
            }
        }

        return $hierarchy;
    }

    public function getRoles()
    {
        return $this->buildRolesTree();
    }
}
