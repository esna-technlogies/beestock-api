<?php

namespace CommonServices\UserServiceBundle\Utility\Aws;
use Aws\Sns\SnsClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SendSMS
 * @package CommonServices\UserServiceBundle\Utility\Aws
 */
class Sns
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @var SnsClient
     */
    private $snsClient;


    /**
     * SmsSender constructor.
     * @param ContainerInterface $serviceContainer
     * @param SnsClient $snsClient
     */
    public function __construct(ContainerInterface $serviceContainer, SnsClient $snsClient)
    {
        $this->serviceContainer = $serviceContainer;

        $this->snsClient = $snsClient;
    }

    /**
     * @param string $user
     * @param string $eventName
     */
    public function publishUserEvent(string $user, string $eventName)
    {
        $userArn = $this->serviceContainer->getParameter('aws_s3_sns_user_arn');

        $this->snsClient->publish(array(
            'TopicArn' => $userArn,
            'Message' => $user.'___'.$eventName,
            'Subject' => $eventName,
            )
        );
    }
}