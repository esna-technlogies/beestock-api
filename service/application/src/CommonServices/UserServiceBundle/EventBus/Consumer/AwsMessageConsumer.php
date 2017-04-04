<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 03/04/2017
 * Time: 2:57 PM
 */

namespace CommonServices\UserServiceBundle\EventBus\Consumer;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AwsMessageConsumer
 * @package CommonServices\UserServiceBundle\EventBus\Consumer
 */
class AwsMessageConsumer
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;


    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function consume(string $queueUrl, int $count =10)
    {
        /** @var  \Aws\Sns\SnsClient  $sqsServiceClient */
        $sqsServiceClient = $this->serviceContainer->get('aws.sns');

        $request =[
            'Message' => 'hello there :)',
            'PhoneNumber' => '+491628596354',
        ];

        $sqsServiceClient->publish($request);
    }
}