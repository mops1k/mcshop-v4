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
    protected function isGranted($role)
    {
        return $this->authorizationChecker->isGranted($role);
    }

    /**
     * @param string $route_name
     * @param array  $parameters
     *
     * @param bool   $referenceType
     *
     * @return string
     */
    protected function generateUrlByRouteName($route_name, $parameters = [], $referenceType = Router::ABSOLUTE_PATH)
    {
        return $this->router->generate($route_name, $parameters, $referenceType);
    }

    public function setBuilder(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return BuilderInterface
     */
    protected function getBuilder()
    {
        $this->builder->clearMenu();
        return $this->builder;
    }

    /**
     * @return string
     */
    protected function getClassName()
    {
        return get_class($this);
    }

    /**
     * @param       $name
     * @param array $arguments
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($name, array $arguments = [])
    {
        $methodName = $name . 'Menu';
        if (!method_exists($this, $methodName)) {
            throw new \Exception('Menu "' . $name . '" in ' . static::getClassName() . ' does not exists');
        }

        return call_user_func_array([$this, $methodName], $arguments);
    }
}