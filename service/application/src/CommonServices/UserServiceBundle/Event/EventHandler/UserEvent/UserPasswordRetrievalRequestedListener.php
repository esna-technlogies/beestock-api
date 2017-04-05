<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\EventBus\Message\UserPasswordRetrievalRequestRequestMessage;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\lib\ChangeRequestsService;
use CommonServices\UserServiceBundle\lib\UserManagerService;
use CommonServices\UserServiceBundle\lib\UserSecurityService;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserPasswordRetrievalRequestedListener
 * @package CommonServices\UserServiceBundle\Event\EventHandler\UserEvent
 */
class UserPasswordRetrievalRequestedListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ChangeRequestsService
     */
    private $changeRequestsService;
    /**
     * @var UserManagerService
     */
    private $userManagerService;

    /**
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     * @param UserManagerService $userManagerService
     * @param ChangeRequestsService $changeRequestsService
     */
    public function __construct(
        ContainerInterface $container,
        UserManagerService $userManagerService,
        ChangeRequestsService $changeRequestsService
    )
    {
        $this->serviceContainer = $container;
        $this->changeRequestsService   = $changeRequestsService;
        $this->userManagerService = $userManagerService;
    }

    /**
     * @param Event $event
     *
     * @throws InvalidArgumentException
     */
    public function onUserPasswordRetrievalRequested(Event $event)
    {
        /** @var UserPasswordRetrievalRequestedEvent $event */
        $user = $event->getUser();
        $verificationCode = UserSecurityService::generateRandomString(6);

        $smsChangeRequest = new UserPasswordRetrievalRequestRequestMessage(
            $this->changeRequestsService,
            $user,
            $verificationCode,
            'send_sms'
        );
        $this->changeRequestsService->updateChangeRequest($smsChangeRequest->generateRequest());

        $emailChangeRequest = new UserPasswordRetrievalRequestRequestMessage(
            $this->changeRequestsService,
            $user,
            $verificationCode,
            'send_email'
        );
        $this->changeRequestsService->updateChangeRequest($emailChangeRequest->generateRequest());

        $user->getAccessInfo()->setLastPasswordRetrievalRequest(time());
        $this->userManagerService->saveUser($user);

        /*
        $message = json_encode([
            'uuid'      => $user->getUuid(),
            'email'     => $user->getEmail(),
            'mobile'    => $user->getMobileNumber()->getInternationalNumber(),
            'eft'       => $event->getEventFiringTime(),
            'verificationCode'  => $verificationCode,
        ]);

        $requestQueueUrl    = $this->serviceContainer->getParameter('aws_sqs_user_password_change_request_queue_url');
        $sqsMessageProducer = $this->serviceContainer->get('aws.sqs.message_producer');

        $sqsMessageProducer->publish(
            $message,
            $requestQueueUrl,
            str_replace('.', '_', UserPasswordRetrievalRequestedEvent::NAME)
        );

        $commandRunner = $this->serviceContainer->get('user_service.command.standard_command_runner');
        $message ='You recently requested to retrieve your password, please use verification code:'.PHP_EOL;
        $smsInput = new ArrayInput([
                    'command' => 'user-service:send-user-notification-sms',
                    'message' => $message.PHP_EOL.$verificationCode,
                    'mobileNumber' => $user->getMobileNumber()->getInternationalNumber(),
        ]);
        $commandRunner->execute($smsInput);
        $emailInput = new ArrayInput([
                    'command' => 'user-service:send-user-email',
                    'message' => $message.PHP_EOL.$verificationCode,
                    'email' => $user->getEmail(),
        ]);
        $commandRunner->execute($emailInput);

        */
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserPasswordRetrievalRequestedEvent::NAME => array(
                array('onUserPasswordRetrievalRequested', 1),
            ),
        );
    }
}