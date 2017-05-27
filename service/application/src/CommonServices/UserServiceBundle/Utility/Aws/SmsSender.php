<?php

namespace CommonServices\UserServiceBundle\Utility\Aws;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SendSMS
 * @package CommonServices\UserServiceBundle\Utility\Aws
 */
class SmsSender
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * SmsSender constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;

    }

    /**
     * @param string $message
     * @param string $internationalPhoneNumber
     */
    public function send(string $message, string $internationalPhoneNumber)
    {
        /** @var  \Aws\Sns\SnsClient  $sqsServiceClient */
        $sqsServiceClient = $this->serviceContainer->get('aws.sns');

        $request =[
            'Message'     => $message,
            'PhoneNumber' => $internationalPhoneNumber
        ];
        $sqsServiceClient->publish($request);
    }
}