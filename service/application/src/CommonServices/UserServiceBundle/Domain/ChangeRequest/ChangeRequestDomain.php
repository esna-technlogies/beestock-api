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
     * @var ChangeRequestRepository
     */
    private $changeRequestRepository;

    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    const USER_NOTIFICATION_EMAIL = 'email';
    const USER_NOTIFICATION_SMS   = 'sms';
    const USER_NOTIFICATION_NONE  = 'none';

    /**
     * UserChangeRequestsService constructor.
     * @param ContainerInterface $serviceContainer
     * @param ChangeRequestRepository $changeRequestRepository
     */
    public function __construct(ContainerInterface $serviceContainer, ChangeRequestRepository $changeRequestRepository)
    {
        $this->serviceContainer = $serviceContainer;
        $this->changeRequestRepository = $changeRequestRepository;
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
     * @param string $action
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
        string $action,
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
        $changeRequest->setAction($action);

        $changeRequest->setOldValue($oldValue);
        $changeRequest->setNewValue($newValue);

        return $changeRequest;
    }
}







