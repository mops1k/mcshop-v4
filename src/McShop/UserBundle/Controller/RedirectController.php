<?php

namespace McShop\UserBundle\Controller;

use McShop\Core\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class RedirectController
 * @package McShop\UserBundle\Controller
 */
class RedirectController extends BaseController
{
    public function redirectAction(Request $request, $route, $permanent = false, $ignoreAttributes = false)
    {
        if ('' == $route) {
            throw new HttpException($permanent ? 410 : 404);
        }

        $attributes = array();
        if (false === $ignoreAttributes || is_array($ignoreAttributes)) {
            $attributes = $request->attributes->get('_route_params') + [ '_locale' => $request->get('_locale') ];
            unset($attributes['route'], $attributes['permanent'], $attributes['ignoreAttributes']);
            if ($ignoreAttributes) {
                $attributes = array_diff_key($attributes, array_flip($ignoreAttributes));
            }
        }

        return new RedirectResponse(
            $this->container->get('router')->generate($route, $attributes, UrlGeneratorInterface::ABSOLUTE_URL),
            $permanent ? 301 : 302
        );
    }
}
