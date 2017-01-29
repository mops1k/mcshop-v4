<?php
namespace McShop\UserBundle\Services\Helper;

use McShop\UserBundle\Entity\User;

/**
 * Class UserEmailHelper
 * @package McShop\UserBundle\Services\Helper
 */
class UserEmailHelper
{
    /** @var User */
    private $user;

    /** @var  \Twig_Environment */
    private $twig;

    /** @var  \Swift_Mailer */
    private $mailer;

    /**
     * UserEmailHelper constructor.
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @param $theme
     * @param $from
     * @param $template
     * @param array $templateArguments
     */
    public function send($theme, $from, $template, array $templateArguments = [])
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($theme)
            ->setTo($this->user->getEmail())
            ->setFrom($from)
            ->setCharset('UTF-8')
            ->setBody(
                $this->twig->render($template, $templateArguments),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }

    /**
     * @param User $user
     * @return UserEmailHelper
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}