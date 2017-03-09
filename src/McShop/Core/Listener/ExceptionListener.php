<?php
namespace McShop\Core\Listener;

use McShop\Core\Twig\Title;
use McShop\SettingBundle\Service\SettingHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
    /** @var \Twig_Environment */
    private $twig;
    /** @var SettingHelper */
    private $setting;
    /** @var Title */
    private $title;
    /** @var string */
    private $env;

    /**
     * ExceptionListener constructor.
     * @param \Twig_Environment $twig
     * @param SettingHelper $setting
     */
    public function __construct(\Twig_Environment $twig, SettingHelper $setting, Title $title, $env)
    {
        $this->twig = $twig;
        $this->setting = $setting;
        $this->title = $title;
        $this->env = $env;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (in_array($this->env, ['dev', 'test'])) {
            return;
        }

        $this->title->setValue('system.error')->setAttributes(['@code@' => $event->getException()->getStatusCode() ]);

        $response = new Response(
            $this->twig->render(':' . $this->setting->get('template', 'Default') . ':errors.html.twig', [
                'exception' => $event->getException(),
            ])
        );

        $event->setResponse($response);
    }
}
