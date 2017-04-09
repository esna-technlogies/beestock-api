<?php

namespace CommonServices\UserServiceBundle\Utility\Email;

/**
 * Class SwiftMailSender
 * @package CommonServices\UserServiceBundle\Utility\Email
 */
class SwiftMailSender extends AbstractEmailSender
{
    /**
     * @inheritdoc
     */
    public function send(string $emailAddress, string $subject, string $emailTemplateName, array $emailVariables) : void
    {
        $body = $this->renderTemplate($emailTemplateName, $emailVariables);
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->container->getParameter('mailer_user'))
            ->setTo($emailAddress)
            ->setBody($body,'text/html')
        ;
        $this->container->get('swiftmailer.mailer.default')->send($message);
    }

    /**
     * @param string $templateName
     * @param array $emailVariables
     * @return string
     */
    public function renderTemplate(string $templateName, array $emailVariables)
    {
        return $this->twig->render(
            'UserServiceBundle:Emails/'. $templateName,
            $emailVariables
        );
    }
}