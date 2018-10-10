<?php
namespace McShop\MenuBundle\Model\Common;

use McShop\MenuBundle\Model\BuilderInterface;
use McShop\MenuBundle\Model\MenuInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class AbstractMenu implements MenuInterface
{
    /** @var BuilderInterface */
    protected $builder;
    /** @var Router */
    protected $router;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /** @var array */
    protected $menu = [];

    /**
     * Navigation constructor.
     *
     * @param Router                        $router
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        Router $router,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param string     $role
     *
     * @return bool
     */
    protected function isGranted(string $role): bool
    {
        return $this->authorizationChecker->isGranted($role);
    }

    /**
     * @param string $route_name
     * @param array $parameters
     *
     * @param int $referenceType
     *
     * @return string
     */
    protected function generateUrlByRouteName(string $route_name, array $parameters = [], int $referenceType = Router::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route_name, $parameters, $referenceType);
    }

    /**
     * @param BuilderInterface $builder
     * @return MenuInterface
     */
    public function setBuilder(BuilderInterface $builder): MenuInterface
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return BuilderInterface
     */
    protected function getBuilder(): BuilderInterface
    {
        $this->builder->clearMenu();

        return $this->builder;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return array
     * @throws \Exception
     */
    public function get(string $name, array $arguments = []): array
    {
        $methodName = $name . 'Menu';
        if (!method_exists($this, $methodName)) {
            throw new \Exception('Menu "' . $name . '" in ' . static::class . ' does not exists');
        }

        return call_user_func_array([$this, $methodName], $arguments);
    }
}