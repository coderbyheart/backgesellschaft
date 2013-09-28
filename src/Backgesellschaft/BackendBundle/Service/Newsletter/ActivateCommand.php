<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Backgesellschaft | http://backgesellschaft.de/
 */

namespace Backgesellschaft\BackendBundle\Service\Newsletter;

use Backgesellschaft\BackendBundle\Entity\Newsletter\Subscription;

class ActivateCommand
{
    /**
     * @var Subscription
     */
    public $subscription;
}