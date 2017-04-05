<?php

namespace CommonServices\UserServiceBundle\EventBus\Message;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\lib\ChangeRequestsService;

/**
 * Class SmsMessage
 * @package CommonServices\UserServiceBundle\EventBus\Message
 */
class UserPasswordRetrievalRequestRequestMessage implements RequestMessageTypeInterface
{
    /**
     * @var ChangeRequestsService
     */
    private $changeRequestsService;
    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $verificationCode;
    /**
     * @var string
     */
    private $messageType;

    /**
     * @inheritdoc
     */
    public function __construct(
        ChangeRequestsService $changeRequestsService,
        User $user,
        string $verificationCode,
        string $messageType
    )
    {
        $this->changeRequestsService = $changeRequestsService;
        $this->user = $user;
        $this->verificationCode = $verificationCode;
        $this->messageType = $messageType;
    }

    /**
     * @inheritdoc
     */
    public function generateRequest() : ChangeRequest
    {
        $changeRequest = $this->changeRequestsService->createChangeRequest();

        $changeRequest->setEventFiringTime(time());
        $changeRequest->setEventLifeTime(time() + (1 * 60 * 60));
        $changeRequest->setEventName(UserPasswordRetrievalRequestedEvent::NAME);
        $changeRequest->setUuid($this->user->getUuid());
        $changeRequest->setVerificationCode($this->verificationCode);
        $changeRequest->setAction($this->messageType);

        return $changeRequest;
    }

    /**
     * @inheritdoc
     */
    public function toJson(): string
    {
        // TODO: Implement toJson() method.
    }

}