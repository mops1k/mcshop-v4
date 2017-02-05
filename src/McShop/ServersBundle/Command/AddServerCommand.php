<?php

namespace McShop\ServersBundle\Command;

use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use McShop\ServersBundle\Form\ServerType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddServerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('mc_shop:servers:add')
            ->setDescription('Add server to the system');
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
        $server = $formHelper->interactUsingForm(ServerType::class, $input, $output);

        $manager = $this->getContainer()->get('doctrine')->getManagerForClass(get_class($server));

        $manager->persist($server);
        $manager->flush();

        $output->writeln('Server succesfully added to database!');
    }
}
