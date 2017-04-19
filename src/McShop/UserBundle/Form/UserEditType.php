<?php

namespace McShop\UserBundle\Form;

use Doctrine\ORM\EntityRepository;
use McShop\UserBundle\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class UserEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null === $options['current_user']) {
            throw new \LogicException('Unknown current user');
        }

        $builder
            ->add('username', TextType::class, [
                'label' => 'form.registration.username',
                'disabled'  => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.registration.email',
                'constraints'   => [
                    new Email(),
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validation.password.must_match',
                'first_options' => ['label' => 'form.registration.password'],
                'second_options' => ['label' => 'form.registration.re_password'],
                'required'      => false,
            ])
            ->add('roles', EntityType::class, [
                'label' => 'user.manage.roles',
                'class' => 'McShop\UserBundle\Entity\Role',
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('r');
                    $ids = $this->getAllChildrensIds($options['current_user']->getRoles());

                    $qb->select('r')
                        ->where($qb->expr()->in('r.id', $ids))
                    ;

                    return $qb;
                },
                'multiple'  => true,
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'McShop\UserBundle\Entity\User',
            'current_user' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mcshop_userbundle_user_edit';
    }

    /**
     * @param Role[] $roles
     * @param array $data
     *
     * @return array
     */
    private function getAllChildrensIds($roles, $data = [])
    {
        foreach ($roles as $role) {
            $data[] = $role->getId();
            if (count($role->getChildrens()) !== 0) {
                $data = array_merge_recursive($data, $this->getAllChildrensIds($role->getChildrens(), $data));
            }
        }

        return array_unique($data);
    }
}
