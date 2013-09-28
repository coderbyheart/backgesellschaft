<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Backgesellschaft | http://backgesellschaft.de/
 */

namespace Backgesellschaft\BackendBundle\Command;

use Backgesellschaft\BackendBundle\Service\Newsletter\SendConfirmationMailCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendConfirmSubscriptionMailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('backgesellschaft:newsletter:confirm')
            ->setDescription('Send newsletter subscription confirmation emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Backgesellschaft\BackendBundle\Entity\Newsletter\SubscriptionRepository $repo */
        $repo = $this->getContainer()->get('backgesellschaft.backend.repo.subscription');
        /** @var \LiteCQRS\Bus\CommandBus $commandBus */
        $commandBus = $this->getContainer()->get('command_bus');
        foreach ($repo->getNewSubscriptions() as $subscription) {
            if ($output->getVerbosity() === OutputInterface::VERBOSITY_VERBOSE) $output->writeln($subscription->getEmail());
            $command                = new SendConfirmationMailCommand();
            $command->subscription  = $subscription;
            $command->schemeAndHost = $this->getContainer()->getParameter('scheme_and_host');
            $commandBus->handle($command);
        }
    }
}