<?php

namespace CommonServices\UserServiceBundle\Domain\ChangeRequest;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Factory\ChangeRequestFactory;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChangeRequestService
 * @package CommonServices\UserServiceBundle\Domain\ChangeRequest\Manager
 */
class ChangeRequestService
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
     * ChangeRequestManager constructor.
     * @param ContainerInterface $container
     * @param ChangeRequestRepository $changeRequestRepository
     */
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
     * @param string $oldValue
     * @param string $newValue
     * @param string $changeProcessorName
     *
     * @return ChangeRequest
     */
    public function createPendingChangeRequest
    (
        User $user,
        string $verificationCode,
        string $eventName,
        int $eventLifeTime,
        $oldValue = null,
        $newValue = null,
        string $changeProcessorName
    )
    {
        $changeRequestFactory = new ChangeRequestFactory($this->container, $this->changeRequestRepository);

        $changeRequest = $changeRequestFactory->createChangeRequestFromEventInfo(
            $user,
            $verificationCode,
            $eventName,
            $eventLifeTime,
            $oldValue,
            $newValue,
            $changeProcessorName
        );

        return $changeRequest;
    }

    /**
     * @param array $pendingRequests
     * persistPendingRequests
     */
    public function processPendingRequests(array $pendingRequests)
    {
        $eventBusService = $this->container->get('user_service.event_bus_service');

        foreach ($pendingRequests as $changeRequest)
        {
            $this->changeRequestRepository->save($changeRequest);
            $eventBusService->publish($changeRequest);
        }
    }
}