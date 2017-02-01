<?php
namespace McShop\UserBundle\Menu;

use McShop\MenuBundle\Model\Common\AbstractMenu;
use McShop\UserBundle\Menu\Builder\UserMenuBuilder;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserMenu extends AbstractMenu
{
    /** @var TokenInterface */
    private $token;

    /**
     * UserMenu constructor.
     * @param Router $router
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $token
     */
    public function __construct(
        Router $router,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $token
    ) {
        parent::__construct($router, $authorizationChecker);
        $this->token = $token->getToken();
    }

    /**
     * @return array
     */
    public function userMenu()
    {
        /** @var UserMenuBuilder $builder */
        $builder = $this->getBuilder();
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $builder
                ->addRootItem('user', $this->token->getUsername(), null, [
                    'icon'  => 'fa fa-user'
                ])
                ->addItem('logout', 'user.menu.logout', $this->generateUrlByRouteName('mc_shop_user_logout'), [
                    'icon'  => 'fa fa-sign-out'
                ])
            ;

            return $builder->getMenu();
        }

        $builder
            ->addRootItem('signin', 'user.menu.sign-in', $this->generateUrlByRouteName('mc_shop_user_login'), [
                'icon'  => 'fa fa-sign-in'
            ])
            ->addRootItem('signup', 'user.menu.sign-up', $this->generateUrlByRouteName('mc_shop_user_registration'), [
                'icon'  => 'fa fa-key'
            ])
        ;

        return $builder->getMenu();
    }
}