<?php
namespace McShop\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * Redirect to referer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToReferer()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $url = $request->headers->get('referer') === null ?
            $this->generateUrl('homepage', [ '_locale' => $request->get('_locale')]) : $request->headers->get('referer')
        ;
        return $this->redirect($url);
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function isAuthenticatedErrorShow()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash(
                'info',
                $this->get('translator')->trans('login.error.already_logged_in')
            );
            return $this->redirectToReferer();
        }

        return null;
    }

    /**
     * Translates the given message.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     *
     * @return string The translated string
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     */
    public function trans($id, array $parameters = [], $domain = 'messages')
    {
        return $this->get('translator')->trans($id, $parameters, $domain);
    }

    /**
     * @param $id
     * @param $number
     * @param array $options
     * @param $domain
     * @param string $domain
     * @return string
     */
    protected function transChoice($id, $number, array $options = [], $domain = 'messages')
    {
        return $this->get('translator')->transChoice($id, $number, $options, $domain);
    }

    /**
     * @param $name
     * @param null $defaultValue
     * @param bool $loadFromCache
     * @return null|string
     */
    protected function getSetting($name, $defaultValue = null, $loadFromCache = true)
    {
        return $this->get('mc_shop.setting.helper')->get($name, $defaultValue, $loadFromCache);
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if (!$this->get('filesystem')
            ->exists(
                $this->getParameter('kernel.root_dir') . '/Resources/views/' . $this->getSetting('template', 'Default')
            )) {
            throw $this->createNotFoundException(
                sprintf('Template "%s" does not exists!', $this->getSetting('template'))
            );
        }

        $view = preg_replace_callback('/^:(\w+)\//i', function () {
            return sprintf(':%s/', $this->getSetting('template'));
        }, $view, 1);

        return parent::render($view, $parameters, $response);
    }
}
