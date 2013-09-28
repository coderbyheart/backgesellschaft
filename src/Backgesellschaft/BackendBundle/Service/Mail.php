<?php

namespace Backgesellschaft\BackendBundle\Service;

use Backgesellschaft\BackendBundle\Service\Mail\SendTemplateMailCommand;
use Backgesellschaft\BackendBundle\Content\ContentReader;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Templating\TemplateReference;

class Mail
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $mailFromName;

    /**
     * @var string
     */
    private $mailFromEmail;

    /**
     * @var string
     */
    private $locale;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param      string   $mailFromEmail
     * @param      string   $mailFromName
     * @param string $locale
     */
    public function __construct(\Swift_Mailer $mailer, $mailFromEmail, $mailFromName, $locale)
    {
        $this->mailer        = $mailer;
        $this->mailFromEmail = $mailFromEmail;
        $this->mailFromName  = $mailFromName;
        $this->locale = $locale;
    }

    public function sendTemplateMail(SendTemplateMailCommand $command)
    {
        $env     = new \Twig_Environment(new \Twig_Loader_String());
        $subject = $env->render($this->getTemplate($command->file, 'subject'), $command->subjectData);
        $body    = $env->render($this->getTemplate($command->file, 'body'), $command->bodyData);

        $message = \Swift_Message::newInstance();
        $message->setCharset('UTF-8');
        $message->setFrom($this->mailFromEmail, $this->mailFromName)
            ->setSubject($subject)
            ->setTo($command->email)
            ->setBody($body);

        echo $body;
        echo $subject;

        $this->mailer->send($message);
    }

    protected function getTemplate($file, $type)
    {
        return file_get_contents(join(array(__DIR__, '..', 'Resources', 'Email', $this->locale, $file . '.' . $type . '.txt'), DIRECTORY_SEPARATOR));
    }
}
