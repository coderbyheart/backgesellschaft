<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Backgesellschaft | http://backgesellschaft.de/
 */

namespace Backgesellschaft\WebBundle\Controller;

use Backgesellschaft\BackendBundle\Entity\Newsletter\SubscriptionRepository;
use Backgesellschaft\BackendBundle\Service\Newsletter\ActivateCommand;
use Backgesellschaft\BackendBundle\Service\Newsletter\SubscribeCommand;
use Backgesellschaft\WebBundle\Content\ContentReader;
use Backgesellschaft\WebBundle\Form\NewsletterSubscribeModel;
use Backgesellschaft\WebBundle\Form\NewsletterSubscribeType;
use LiteCQRS\Bus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Manages newsletter subscriptions.
 */
class NewsletterController
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \LiteCQRS\Bus\CommandBus
     */
    private $commandBus;

    /**
     * @var \Backgesellschaft\BackendBundle\Entity\Newsletter\SubscriptionRepository
     */
    private $repo;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, CommandBus $commandBus, SubscriptionRepository $repo)
    {
        $this->formFactory = $formFactory;
        $this->router      = $router;
        $this->commandBus  = $commandBus;
        $this->repo        = $repo;
    }

    /**
     * @param Request $request
     *
     * @Template()
     */
    public function subscribeAction(Request $request)
    {
        $form = $this->formFactory->create(new NewsletterSubscribeType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            /* @var NewsletterSubscribeModel $formData */
            $formData     = $form->getData();
            $subscription = $this->repo->getSubscription($formData->email);
            if ($subscription->isDefined()) {
                return new RedirectResponse($this->router->generate('bg_newsletter_already_subscribed'));
            }
            $command        = new SubscribeCommand();
            $command->email = $formData->email;
            $this->commandBus->handle($command);
            return new RedirectResponse($this->router->generate('bg_newsletter_ok'));
        }
        return array(
            'form' => $form->createView(),
        );
    }

    public function confirmAction($id, $key)
    {
        $subscription = $this->repo->getSubscriptionByIdAndKey($id, $key);
        if ($subscription->isEmpty()) {
            throw new NotFoundHttpException('Unknown subscription.');
        }
        if ($subscription->get()->getConfirmed()) {
            throw new GoneHttpException('Already confirmed.');
        }
        $command               = new ActivateCommand();
        $command->subscription = $subscription->get();
        $this->commandBus->handle($command);
        return new RedirectResponse($this->router->generate('bg_newsletter_confirmed'));
    }
}
