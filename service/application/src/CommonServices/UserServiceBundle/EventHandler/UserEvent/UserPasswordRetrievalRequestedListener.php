<?php

namespace CommonServices\UserServiceBundle\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Domain\ChangeRequest\ChangeRequestDomain;
use CommonServices\UserServiceBundle\Domain\User\UserDomain;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\Utility\Security\RandomCodeGenerator;
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
     * @var ChangeRequestDomain
     */
    private $changeRequestsService;
    /**
     * @var UserDomain
     */
    private $userManagerService;

    /**
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     * @param UserDomain    $userManagerService
     * @param ChangeRequestDomain $changeRequestsService
     */
    public function __construct(
        ContainerInterface $container,
        UserDomain    $userManagerService,
        ChangeRequestDomain $changeRequestsService
    )
    {
        $this->serviceContainer   = $container;
        $this->userManagerService = $userManagerService;
        $this->changeRequestsService   = $changeRequestsService;
    }

    /**
     * @param Event $event
     *
     * @throws InvalidArgumentException
     */
    public function onUserPasswordRetrievalRequested(Event $event)
    {
        /** @var UserPasswordRetrievalRequestedEvent $event */
        $userDocument = $event->getUserDocument();
        $user = $this->userManagerService->getUser($userDocument);
        $verificationCode = RandomCodeGenerator::generateRandomVerificationString(6);

        $requestLifeTime = 1 * 60 * 60;

        // notify user by sms
        $changeRequestSmsNotification = $this->changeRequestsService->generateChangeRequest(
            $userDocument,
            $verificationCode,
            UserPasswordRetrievalRequestedEvent::NAME,
            $requestLifeTime,
            ChangeRequestDomain::USER_NOTIFICATION_SMS
        );
        $user->getAccount()->issueAccountChangeRequest($changeRequestSmsNotification);


        // notify user by email
        $changeRequestEmailNotification = $this->changeRequestsService->generateChangeRequest(
            $userDocument,
            $verificationCode,
            UserPasswordRetrievalRequestedEvent::NAME,
            $requestLifeTime,
            ChangeRequestDomain::USER_NOTIFICATION_EMAIL
        );
        $user->getAccount()->issueAccountChangeRequest($changeRequestEmailNotification);

        $user->getAccount()->setLastPasswordRetrievalRequest(time());

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