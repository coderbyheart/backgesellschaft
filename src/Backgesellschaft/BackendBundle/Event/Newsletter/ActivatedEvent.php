<?php

namespace Backgesellschaft\BackendBundle\Event\Newsletter;

use LiteCQRS\Bus\EventMessageHeader;
use LiteCQRS\DomainEvent;

class ActivatedEvent implements DomainEvent
{
    /**
     * @var \Backgesellschaft\BackendBundle\Entity\Newsletter\Subscription
     */
    public $subscription;

    private $messageHeader;

    public function __construct()
    {
        $this->messageHeader = new EventMessageHeader();
    }

    public function getEventName()
    {
        return 'Activated';
    }

    public function getMessageHeader()
    {
        return $this->messageHeader;
    }

    public function getAggregateId()
    {
        return $this->messageHeader->aggregateId;
    }


}