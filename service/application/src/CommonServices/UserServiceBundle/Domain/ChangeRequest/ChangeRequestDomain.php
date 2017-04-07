<?php

namespace CommonServices\UserServiceBundle\Domain\ChangeRequest;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChangeRequestDomain
 * @package CommonServices\UserServiceBundle\lib
 */
class ChangeRequestDomain
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ChangeRequestRepository
     */
    private $changeRequestRepository;

    /**
     * UserChangeRequestsService constructor.
     * @param ContainerInterface $container
     * @param ChangeRequestRepository $changeRequestRepository
     */
    public function __construct(ContainerInterface $container, ChangeRequestRepository $changeRequestRepository)
    {
        $this->changeRequestRepository = $changeRequestRepository;
        $this->container = $container;
    }

    /**
     * @param ChangeRequest $changeRequest
     */
    public function deleteChangeRequest(ChangeRequest $changeRequest)
    {
        $this->changeRequestRepository->delete($changeRequest);
    }

    /**
     * @param string $action
     * @param int $startPage
     * @param int $limit
     * @return array
     */
    public function getMostRecentRequests(string $action, $startPage = 1, int $limit = 10) : array
    {
        $queryPaginationHandler = $this->changeRequestRepository->findAllChangeRequests($action, $startPage, $limit);

        return $queryPaginationHandler->getQueryResults();
    }

    /**
     * @param User $user
     * @param string $verificationCode
     * @param string $eventName
     * @param int $eventLifeTime
     * @param string $oldValue
     * @param string $newValue
     *
     * @return ChangeRequest
     */
    public function generateChangeRequest
    (
        User $user,
        string $verificationCode,
        string $eventName,
        int $eventLifeTime,
        $oldValue = null,
        $newValue = null
    )
    {
        $changeRequest = new ChangeRequest();

        $changeRequest->setEventFiringTime(time());
        $changeRequest->setEventLifeTime(time() + ($eventLifeTime));
        $changeRequest->setEventName($eventName);
        $changeRequest->setUuid($user->getUuid());
        $changeRequest->setVerificationCode($verificationCode);
        $changeRequest->setOldValue($oldValue);
        $changeRequest->setNewValue($newValue);

        return $changeRequest;
    }

    /**
     * @param ChangeRequest $changeRequest
     * @param string $producerName
     */
    public function publishChangeRequest(ChangeRequest $changeRequest, string $producerName)
    {
        $this->container
            ->get('user_service.event_bus_service')
            ->publish($changeRequest, $producerName);
    }
}







