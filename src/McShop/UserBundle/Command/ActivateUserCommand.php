<?php

namespace McShop\UserBundle\Command;

use McShop\UserBundle\Entity\Token;
use McShop\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActivateUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('mc_shop:user:activate')
            ->setDescription('Activate user account')
            ->addArgument('user', InputArgument::REQUIRED, 'Username to activate')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        /** @var User $user */
        $user = $doctrine
            ->getManagerForClass('McShopUserBundle:User')
            ->getRepository('McShopUserBundle:User')
            ->findOneByUsername($input->getArgument('user'))
        ;
        $user->setActive(true);

        if ($user) {
            $doctrine->getManagerForClass('McShopUserBundle:User')->persist($user);

            /** @var Token $token */
            $token = $doctrine->getManagerForClass('McShopUserBundle:Token')
                ->getRepository('McShopUserBundle:Token')
                ->findOneBy([
                    'user'  => $user,
                    'kind'  => Token::KIND_REGISTER
                ])
            ;

            if ($token) {
                $token->setActive(false);
                $doctrine->getManagerForClass('McShopUserBundle:Token')->persist($token);
            }

            $doctrine->getManagerForClass('McShopUserBundle:User')->flush();
            $output->writeln('User successfull activated!');
        } else {
            $output->writeln('User not found!');
        }
    }
}
