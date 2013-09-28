<?php

namespace Backgesellschaft\BackendBundle\Service\Mail;

class SendTemplateMailCommand
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var array
     */
    public $subjectData = array();

    /**
     * @var string
     */
    public $file;

    /**
     * @var array
     */
    public $bodyData = array();
}