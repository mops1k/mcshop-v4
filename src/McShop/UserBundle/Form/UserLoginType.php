<?php declare(strict_types=1);


namespace McShop\UserBundle\Form;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Form type for use with the Security component's form-based authentication
 * listener.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
class UserLoginType extends AbstractType
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;
    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'login.username',
                'attr' => [
                    'placeholder' => 'login.password',
                ],
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'login.password',
                'attr' => [
                    'placeholder' => 'login.password',
                ],
            ])
            ->add('captha', CaptchaType::class, [
                'label' => 'form.registration.captcha',
                'attr' => [
                    'placeholder'   => 'form.registration.captcha_help'
                ]
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'login.remember_me',
            ])
        ;

        $authUtils = $this->authenticationUtils;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($authUtils) {
            // get the login error if there is one
            $error = $authUtils->getLastAuthenticationError();
            if ($error) {
                $event->getForm()->addError(new FormError($error->getMessage()));
            }
            $event->setData(array_replace((array) $event->getData(), array(
                '_username' => $authUtils->getLastUsername(),
            )));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ]);
    }


    public function getBlockPrefix()
    {
        return '';
    }
}
