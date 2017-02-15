<?php
namespace McShop\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @param $id
     * @param array $options
     * @param string $domain
     * @return string
     */
    public function trans($id, array $options = [], $domain = 'messages')
    {
        return $this->get('translator')->trans($id, $options, $domain);
    }

    /**
     * @param $id
     * @param $number
     * @param array $options
     * @param $domain
     * @param string $domain
     * @return string
     */
    public function transChoice($id, $number, array $options = [], $domain = 'messages')
    {
        return $this->get('translator')->transChoice($id, $number, $options, $domain);
    }
}
