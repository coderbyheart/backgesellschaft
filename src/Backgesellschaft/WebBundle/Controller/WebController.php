<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Backgesellschaft | http://backgesellschaft.de/
 */

namespace Backgesellschaft\WebBundle\Controller;

use Backgesellschaft\BackendBundle\Exception\FileNotFoundException;
use Backgesellschaft\WebBundle\Form\NewsletterSubscribeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Renders the page.
 */
class WebController
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Render the index page.
     *
     * @param Request $request
     *
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form             = $this->formFactory->create(new NewsletterSubscribeType());
        return array(
            'newsletter_form' => $form->createView()
        );
    }
}
