<?php

namespace CommonServices\UserServiceBundle\EventBus\Message;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\lib\ChangeRequestsService;

/**
 * Interface MessageTypeInterface
 * @package CommonServices\UserServiceBundle\EventBus\Message
 */
interface RequestMessageTypeInterface
{
    /**
     * @param ChangeRequestsService $changeRequestsService
     * @param User $user
     * @param string $verificationCode
     * @param string $messageType
     */
    public function __construct(
        ChangeRequestsService $changeRequestsService,
        User $user,
        string $verificationCode,
        string $messageType
    );

    /**
     * @return string
     */
    public function toJson(): string ;

    /**
     * @return ChangeRequest
     */
    public function generateRequest() : ChangeRequest;

}