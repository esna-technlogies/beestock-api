<?php

namespace CommonServices\UserServiceBundle\Utility\Email;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractEmailSender
 * @package CommonServices\UserServiceBundle\Utility\Email
 */
abstract class AbstractEmailSender
{
    /**
     * EmailSenderInterface constructor.
     * @param ContainerInterface $container
     * @param \Twig_Environment $twig
     */
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $container, \Twig_Environment $twig)
    {
        $this->container = $container;
        $this->twig = $twig;
    }

    /**
     * @param string $emailAddress
     * @param string $subject
     * @param string $emailTemplateName
     * @param array $emailVariables
     * @return mixed
     */
    abstract public function send(string $emailAddress, string $subject, string $emailTemplateName, array $emailVariables) : void;

    /**
     * @param string $templateName
     * @param array $emailVariables
     * @return string
     */
    public function renderTemplate(string $templateName, array $emailVariables)
    {
        return $this->twig->render(
            'UserServiceBundle:Emails/'. $templateName,
            array(
                'email' => $emailVariables
            )
        );
    }
}