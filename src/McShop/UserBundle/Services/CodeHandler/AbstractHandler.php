<?php
namespace McShop\UserBundle\Services\CodeHandler;

use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\UserBundle\Entity\Token;
use McShop\UserBundle\Services\Helper\UserEmailHelper;
use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractHandler
{
    /** @var ManagerRegistry */
    protected $doctrine;
    /** @var UserEmailHelper */
    protected $userEmailHelper;
    /** @var TranslatorInterface */
    protected $translator;

    /**
     * AbstractHandler constructor.
     * @param ManagerRegistry $doctrine
     * @param UserEmailHelper $userEmailHelper
     */
    public function __construct(ManagerRegistry $doctrine, UserEmailHelper $userEmailHelper, TranslatorInterface $translator)
    {
        $this->doctrine = $doctrine;
        $this->userEmailHelper = $userEmailHelper;
        $this->translator = $translator;
    }

    /**
     * @param Token $token
     * @return bool
     */
    abstract public function handle(Token $token);
}