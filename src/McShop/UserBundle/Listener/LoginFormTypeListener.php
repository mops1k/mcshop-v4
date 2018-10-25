<?php
namespace McShop\UserBundle\Listener;

use McShop\UserBundle\Form\UserLoginType;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class LoginFormTypeListener
 * @package McShop\UserBundle\Listener
 */
class LoginFormTypeListener extends UsernamePasswordFormAuthenticationListener
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var null|CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * LoginFormTypeListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     * @param SessionAuthenticationStrategyInterface $sessionStrategy
     * @param HttpUtils $httpUtils
     * @param $providerKey
     * @param AuthenticationSuccessHandlerInterface $successHandler
     * @param AuthenticationFailureHandlerInterface $failureHandler
     * @param array $options
     * @param LoggerInterface|null $logger
     * @param EventDispatcherInterface|null $dispatcher
     * @param CsrfTokenManagerInterface|null $csrfTokenManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey, AuthenticationSuccessHandlerInterface $successHandler, AuthenticationFailureHandlerInterface $failureHandler, array $options = array(), LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null, CsrfTokenManagerInterface $csrfTokenManager = null)
    {
        parent::__construct(
            $tokenStorage,
            $authenticationManager,
            $sessionStrategy,
            $httpUtils,
            $providerKey,
            $successHandler,
            $failureHandler,
            array_merge($options, [
                'username_parameter' => '_username',
                'password_parameter' => '_password',
                'csrf_parameter'     => '_csrf_token',
                'captcha'            => 'captcha',
                'intention'          => 'authenticate',
                'post_only'          => true,
            ]),
            $logger,
            $dispatcher,
            $csrfTokenManager
        );

        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     * @throws \Exception
     */
    protected function attemptAuthentication(Request $request)
    {
        $session = $request->getSession();
        $attemtpsCache = json_decode($session->get('login_attempts', '{"attempts":0}'), true);

        $access = true;
        ++$attemtpsCache['attempts'];
        $previousAttempt = new \DateTime();
        if ($attemtpsCache['attempts'] >= $this->options['max_attempts']) {
            if (isset($attemtpsCache['last_attempt'])) {
                $previousAttempt = new \DateTime($attemtpsCache['last_attempt']);
            }
            $previousAttempt->add(
                \DateInterval::createFromDateString($this->options['interval'] . ' minutes')
            );
            $currentAttempt = new \DateTime();
            if ($currentAttempt > $previousAttempt) {
                $attemtpsCache['attempts'] = 0;
                unset($attemtpsCache['last_attempt']);
            } else {
                $attemtpsCache['last_attempt'] = isset($attemtpsCache['last_attempt']) ?
                    $attemtpsCache['last_attempt'] : $currentAttempt->format('d.m.Y H:i:s');
                $access = false;
            }
        }
        $session->set('login_attempts', json_encode($attemtpsCache));

        if (!$access) {
            throw new BadCredentialsException($this->translator->trans('system.message.max_attempts', [
                '@interval@'  => $this->options['interval'],
                '@next_try@'  => $previousAttempt->format('d.m.Y H:i:s')
            ]));
        }

        $form = $this->formFactory->create(UserLoginType::class);
        $form->handleRequest($request);

        $captchaForm = $form->get($this->options['captcha']);
        if (!$captchaForm->isValid()) {
            foreach ($captchaForm->getErrors() as $error) {
                throw new BadCredentialsException($error->getMessage());
            }
        }

        return parent::attemptAuthentication($request);
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addCustomOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
}