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

        /** @var User $user */
        $user = $formHelper->interactUsingForm(UserType::class, $input, $output);

        $role = 'ROLE_USER';
        if ($input->getOption('admin')) {
            $role = 'ROLE_SUPER_ADMIN';
        }
        
        $userHelper = $this->getContainer()->get('mc_shop.user.helper');
        $userHelper
            ->setRoleName($role)
            ->setNewPassword(true)
            ->setUser($user)
            ->save(true)
        ;

        $output->writeln('User created!');
    }
}
