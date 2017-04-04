<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\lib\ChangeRequestsService;
use CommonServices\UserServiceBundle\lib\UserSecurityService;
use CommonServices\UserServiceBundle\lib\Utility\Command\StandardCommandRunner;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
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
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     * @param UserRepository $userRepository
     * @param ChangeRequestsService $changeRequestsService
     */
    public function __construct(
        ContainerInterface $container,
        UserRepository $userRepository,
        ChangeRequestsService $changeRequestsService
    )
    {
        $this->serviceContainer = $container;
        $this->userRepository   = $userRepository;
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
        $user = $event->getUser();
        $verificationCode = UserSecurityService::generateRandomString(6);

        $smsChangeRequest = $this->changeRequestsService->createChangeRequest();

        $smsChangeRequest->setEventFiringTime(time());
        $smsChangeRequest->setEventLifeTime(time() + (1 * 60 * 60));
        $smsChangeRequest->setEventName(UserPasswordRetrievalRequestedEvent::NAME);
        $smsChangeRequest->setUuid($user->getUuid());
        $smsChangeRequest->setVerificationCode($verificationCode);
        $smsChangeRequest->setAction('send_sms');

        $this->changeRequestsService->updateChangeRequest($smsChangeRequest);


        $emailChangeRequest = $this->changeRequestsService->createChangeRequest();

        $emailChangeRequest->setEventFiringTime(time());
        $emailChangeRequest->setEventLifeTime(time() + (1 * 60 * 60));
        $emailChangeRequest->setEventName(UserPasswordRetrievalRequestedEvent::NAME);
        $emailChangeRequest->setUuid($user->getUuid());
        $emailChangeRequest->setVerificationCode($verificationCode);
        $emailChangeRequest->setAction('send_email');

        $this->changeRequestsService->updateChangeRequest($emailChangeRequest);

        $this->updateLastPasswordRetrievalRequest($user);


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
                    'command' => 'user-service:send-user-sms',
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
     * @param $user
     */
    private function updateLastPasswordRetrievalRequest(User $user)
    {
        $accessInfo = $user->getAccessInfo();
        $accessInfo->setLastPasswordRetrievalRequest(time());
        $this->userRepository->save($user);
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