<?php

namespace CommonServices\UserServiceBundle\EventBus\Producer;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserPasswordChangeRequestProducer
 * @package CommonServices\UserServiceBundle\EventBus\Producer
 */
class AwsSqsMessageProducer implements ProducerInterface
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

    /**
     * @inheritdoc
     */
    public function publish(string $publishedMessage, string $queueUrl, string $messageGroupId= '')
    {
        /** @var  \Aws\Sqs\SqsClient  $sqsServiceClient */
        $sqsServiceClient = $this->serviceContainer->get('aws.sqs');

        $request =[
            'DelaySeconds' => 0,
            'MessageAttributes' => [],
            'MessageBody' => $publishedMessage, // REQUIRED
            'MessageDeduplicationId' => md5($publishedMessage),
            'QueueUrl' => $queueUrl
        ];

        if(preg_match("#.fifo$#", $queueUrl)){
            $request ['MessageGroupId' ] = $messageGroupId;
        }
        $sqsServiceClient->sendMessage($request);
    }
}