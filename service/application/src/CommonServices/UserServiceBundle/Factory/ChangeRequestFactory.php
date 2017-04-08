<?php

namespace CommonServices\UserServiceBundle\Factory;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserRequestFactory
 * @package CommonServices\UserServiceBundle\Factory
 */
class ChangeRequestFactory
{
    /**
     * @var ContainerInterface
     */

    private $container;
    /**
     * @var ChangeRequestRepository
     */
    private $changeRequestRepository;

    public function __construct(ContainerInterface $container, ChangeRequestRepository $changeRequestRepository)
    {
        $this->container = $container;
        $this->changeRequestRepository = $changeRequestRepository;
    }

    /**
     * @param User $user
     * @param string $verificationCode
     * @param string $eventName
     * @param int $eventLifeTime
     * @param null $oldValue
     * @param null $newValue
     * @param string $changeProcessorName
     *
     * @return ChangeRequest
     */
    public function createChangeRequestFromEventInfo
    (
        User $user,
        string $verificationCode,
        string $eventName,
        int $eventLifeTime,
        $oldValue = null,
        $newValue = null,
        string $changeProcessorName
    ) : ChangeRequest
    {
        $changeRequest = new ChangeRequest();

        $changeRequest->setEventFiringTime(time());
        $changeRequest->setEventLifeTime(time() + ($eventLifeTime));
        $changeRequest->setEventName($eventName);
        $changeRequest->setUuid($user->getUuid());
        $changeRequest->setVerificationCode($verificationCode);
        $changeRequest->setOldValue($oldValue);
        $changeRequest->setNewValue($newValue);
        $changeRequest->setChangeProcessorName($changeProcessorName);

        return $changeRequest;
    }
}