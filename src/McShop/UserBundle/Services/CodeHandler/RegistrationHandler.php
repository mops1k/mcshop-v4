<?php
namespace McShop\UserBundle\Services\CodeHandler;

use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\UserBundle\Entity\Token;
use McShop\UserBundle\Services\Helper\UserEmailHelper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Translation\TranslatorInterface;

class RegistrationHandler extends AbstractHandler
{
    /** @var Session */
    private $session;
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * RegistrationHandler constructor.
     * @param ManagerRegistry $doctrine
     * @param UserEmailHelper $userEmailHelper
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        ManagerRegistry $doctrine,
        UserEmailHelper $userEmailHelper,
        $from,
        TranslatorInterface $translator,
        Session $session,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($doctrine, $userEmailHelper, $translator, $from);
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param Token $token
     * @return bool
     */
    public function handle(Token $token)
    {
        try {
            $token->setActive(false);
            $token->getUser()->setActive(true);

            $this->doctrine->getManagerForClass(get_class($token))->persist($token);
            $this->doctrine->getManagerForClass(get_class($token))->persist($token->getUser());
            $this->doctrine->getManagerForClass(get_class($token))->flush();

            $usernamePasswordToken = new UsernamePasswordToken($token->getUser(), null, 'main', $token->getUser()->getRoles());

            $this->session->getFlashBag()->add('success', $this->translator->trans('registration.message.code_activated'));

            $this->tokenStorage->setToken($usernamePasswordToken);
            $this->session->set('_security_main', serialize($usernamePasswordToken));

            $this->userEmailHelper->setUser($token->getUser())->send(
                $this->translator->trans('registration.message.email.registration_success'),
                $this->from,
                ':Default/User/Email:account_activated.html.twig'
            );
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}