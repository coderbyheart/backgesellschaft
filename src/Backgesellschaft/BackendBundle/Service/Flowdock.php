<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Backgesellschaft | http://backgesellschaft.de/
 */

namespace Backgesellschaft\BackendBundle\Service;

use Backgesellschaft\BackendBundle\Event\Newsletter\ActivatedEvent;

class Flowdock
{
    /**
     * @var string
     */
    private $apiToken;

    /**
     * Constructor
     *
     * @param string $apiToken
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function onActivated(ActivatedEvent $event)
    {
        $postdata = json_encode(array(
                'source'       => 'Backgesellschaft Website',
                'from_name'    => 'Backgesellschaft Website',
                'from_address' => 'www@backgesellschaft.de',
                'subject'      => $event->subscription->getEmail() . ' subscribed to our newsletter',
                'content'      => 'Good news everyone!<br><br>Someone subscribed to our newsletter!',
                'tags'         => 'newsletter'
            ),
            JSON_FORCE_OBJECT
        );

        $opts = array('http' =>
              array(
                  'method'  => 'POST',
                  'header'  => 'Content-Type: application/json; charset=UTF-8',
                  'content' => $postdata
              )
        );

        $context = stream_context_create($opts);

        file_get_contents(sprintf('https://api.flowdock.com/v1/messages/team_inbox/%s', $this->apiToken), false, $context);
    }
}