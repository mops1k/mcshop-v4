<?php

namespace McShop\UserBundle\Command;

use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use McShop\UserBundle\Entity\User;
use McShop\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('mc_shop:user:new')
            ->setDescription('Create new user')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'register user as administrator')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set locale to English
        $translator = $this->getContainer()->get('translator');
        $translator->setLocale('en');

        /** @var FormHelper $formHelper */
        $formHelper = $this->getHelper('form');
        $orm = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var User $user */
        $user = $formHelper->interactUsingForm(UserType::class, $input, $output);

        $role = $orm->getRepository('McShopUserBundle:Role')->findOneByRole('ROLE_USER');
        if ($input->getOption('admin')) {
            $role = $orm->getRepository('McShopUserBundle:Role')->findOneByRole('ROLE_SUPER_ADMIN');
        }

        if ($role !== null) {
            $user->addRole($role);
        }

        $user
            ->setLocked(false)
            ->setActive(true)
            ->setSalt(uniqid(mt_rand()))
        ;

        $passwordEncoder = $this->getContainer()->get('security.password_encoder');
        $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());

        $user->setPassword($encodedPassword);

        $orm->persist($user);
        $orm->flush();

        $output->writeln('User created!');
    }
}
