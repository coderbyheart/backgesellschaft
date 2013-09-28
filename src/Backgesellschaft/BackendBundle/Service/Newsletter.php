<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Backgesellschaft | http://backgesellschaft.de/
 */

namespace Backgesellschaft\BackendBundle\Service;

use Backgesellschaft\BackendBundle\Service\Mail\SendTemplateMailCommand;
use Backgesellschaft\BackendBundle\Service\Newsletter\ActivateCommand;
use Backgesellschaft\BackendBundle\Service\Newsletter\SendConfirmationMailCommand;
use Backgesellschaft\BackendBundle\Service\Newsletter\SubscribeCommand;
use LiteCQRS\Bus\CommandBus;
use LiteCQRS\Plugin\CRUD\Model\Commands\CreateResourceCommand;
use LiteCQRS\Plugin\CRUD\Model\Commands\UpdateResourceCommand;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

class Newsletter
{
    /**
     * @var \LiteCQRS\Bus\CommandBus
     */
    private $commandBus;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param CommandBus      $commandBus
     * @param RouterInterface $router
     */
    public function __construct(CommandBus $commandBus, RouterInterface $router)
    {
        $this->commandBus = $commandBus;
        $this->router     = $router;
    }

    public function subscribe(SubscribeCommand $command)
    {
        $createSubscriptionCommand        = new CreateResourceCommand();
        $createSubscriptionCommand->class = '\Backgesellschaft\BackendBundle\Entity\Newsletter\Subscription';
        $createSubscriptionCommand->data  = array('email' => $command->email);
        $this->commandBus->handle($createSubscriptionCommand);
    }

    public function sendConfirmationMail(SendConfirmationMailCommand $command)
    {
        $updateCommand        = new UpdateResourceCommand();
        $updateCommand->class = '\Backgesellschaft\BackendBundle\Entity\Newsletter\Subscription';
        $updateCommand->id    = $command->subscription->getId();
        $sr                   = new SecureRandom();
        $key                  = sha1($sr->nextBytes(256), false);
        $updateCommand->data  = array('confirmationKey' => $key);
        // $this->commandBus->handle($updateCommand);

        $emailCommand           = new SendTemplateMailCommand();
        $emailCommand->email    = $command->subscription->getEmail();
        $emailCommand->file     = 'confirm';
        $emailCommand->bodyData = array(
            'subscription'      => $command->subscription,
            'confirmation_link' => rtrim($command->schemeAndHost, '/') . $this->router->generate('bg_newsletter_confirm', array('id' => $command->subscription->getId(), 'key' => $key))
        );
        $this->commandBus->handle($emailCommand);
    }

    public function activate(ActivateCommand $command)
    {
        $updateCommand        = new UpdateResourceCommand();
        $updateCommand->class = '\Backgesellschaft\BackendBundle\Entity\Newsletter\Subscription';
        $updateCommand->id    = $command->subscription->getId();
        $updateCommand->data  = array('confirmed' => 1);
        $this->commandBus->handle($updateCommand);
    }
}