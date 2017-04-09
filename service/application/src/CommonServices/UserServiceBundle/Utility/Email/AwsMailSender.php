<?php

namespace CommonServices\UserServiceBundle\Utility\Email;

/**
 * Class AwsMailSender
 * @package CommonServices\UserServiceBundle\Utility\Email
 */
class AwsMailSender extends AbstractEmailSender
{
    /**
     * @inheritdoc
     */
    public function send(string $emailAddress, string $subject, string $emailTemplateName, array $variables) : void
    {
        // Do something here ...
    }
}