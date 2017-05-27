<?php

namespace CommonServices\UserServiceBundle\Domain\ChangeRequest;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChangeRequestSearchManager
 * @package CommonServices\UserServiceBundle\Domain\ChangeRequest\Manager
 */
class ChangeRequestSearchManager
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
     * @param string $action
     * @param int $startPage
     * @param int $limit
     * @return array
     */
    public function findNotificationRequests(string $action="", $startPage = 1, int $limit = 100) : array
    {
        $queryPaginationHandler = $this->changeRequestRepository->findNewChangeRequests($action, $startPage, $limit);

        return $queryPaginationHandler->getQueryResults();
    }

    /**
     * @param string $user
     *
     * @return ChangeRequest | object
     */
    public function findUserActivationRequest(string $user) : ChangeRequest
    {
        return $this->changeRequestRepository->findOneBy(['user' => $user]);
    }
}