<?php
namespace McShop\StaticPageBundle\Menu;

use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\MenuBundle\Model\Common\AbstractMenu;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PageMenu extends AbstractMenu
{
    /** @var ManagerRegistry */
    private $doctrine;

    /**
     * PageMenu constructor.
     * @param Router $router
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ManagerRegistry $doctrine
     */
    public function __construct(
        Router $router,
        AuthorizationCheckerInterface $authorizationChecker,
        ManagerRegistry $doctrine
    ) {
        parent::__construct($router, $authorizationChecker);
        $this->doctrine = $doctrine;
    }

    public function pageMenu()
    {
        $em = $this->doctrine->getManagerForClass('McShopStaticPageBundle:Page');
        $pages = $em->getRepository('McShopStaticPageBundle:Page')->findAllForMenu();

        $builder = $this->getBuilder();

        foreach ($pages as $page) {
            if ($page->getRole() === null || $this->isGranted($page->getRole()->getRole())) {
                $builder->addItem(
                    $page->getSlug(),
                    $page->getTitle(),
                    $this->generateUrlByRouteName('mc_shop_static_page_view', [
                        'slug'  => $page->getSlug(),
                    ])
                );
            }
        }

        return $builder->getMenu();
    }
}
