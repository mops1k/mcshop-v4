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
                    'img'   => $this->token->getUser()->getAvatarPath(),
                ])
            ;
            $builder->addHeader(
                'user.real_cash',
                [ '@cash@' => $this->token->getUser()->getPurse() !== null ?
                    $this->token->getUser()->getPurse()->getRealCash() : 0 ]
            );
            $builder->addItem(
                'profile',
                'user.menu.profile',
                $this->generateUrlByRouteName('mc_shop_user_profile'),
                [
                    'icon'  => 'fa fa-address-card'
                ]
            );
            if ($this->isGranted('ROLE_STATIC_PAGE_ADD')) {
                $builder
                    ->addItem(
                        'static_page',
                        'page.menu.title',
                        $this->generateUrlByRouteName('mc_shop_static_page_list'),
                        [
                            'icon'  => 'fa fa-code'
                        ]
                    )
                ;
            }
            if ($this->isGranted('ROLE_COUPON_ADMIN')) {
                $builder
                    ->addItem(
                        'coupon_admin',
                        'finance.coupon.menu',
                        $this->generateUrlByRouteName('mc_shop_finance_coupon_admin'),
                        [
                            'icon'  => 'fa fa-hashtag'
                        ]
                    )
                ;
            }

            if ($this->isGranted('ROLE_SERVER_LIST')) {
                $builder
                    ->addItem(
                        'server_list',
                        'server.menu.list',
                        $this->generateUrlByRouteName('mc_shop_servers_list'),
                        [
                            'icon'  => 'fa fa-database'
                        ]
                    )
                ;
            }

            if ($this->isGranted('ROLE_RCON_MANAGMENT')) {
                $builder
                    ->addItem(
                        'rcon_page',
                        'server.menu.rcon',
                        $this->generateUrlByRouteName('mc_shop_servers_rcon_page'),
                        [
                            'icon'  => 'fa fa-terminal'
                        ]
                    )
                ;
            }

            if ($this->isGranted('ROLE_SHOPPING_CART_MANAGE')) {
                $builder
                    ->addItem(
                        'shopping_cart_manage',
                        'shopping_cart.menu.managment_title',
                        $this->generateUrlByRouteName('mc_shop_shopping_cart_manage_index'),
                        [
                            'icon'  => 'fa fa-percent'
                        ]
                    )
                ;
            }

            if ($this->isGranted('ROLE_SETTING_EDIT')) {
                $builder
                    ->addItem(
                        'system_setting',
                        'setting.title',
                        $this->generateUrlByRouteName('mc_shop_setting_homepage'),
                        [
                            'icon'  => 'fa fa-list'
                        ]
                    )
                ;
            }

            $builder->addDivider();
            $builder
                ->addItem('logout', 'user.menu.logout', $this->generateUrlByRouteName('mc_shop_user_logout'), [
                    'icon'  => 'fa fa-sign-out'
                ])
            ;
            $builder
                ->addRootItem(
                    'cart',
                    'shopping_cart.basket.menu',
                    $this->generateUrlByRouteName('mc_shop_shopping_cart_basket'),
                    [
                        'icon'   => 'fa fa-shopping-basket',
                    ]
                )
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
