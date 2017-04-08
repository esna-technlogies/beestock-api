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
    public function getMostRecentRequests(string $action, $startPage = 1, int $limit = 10) : array
    {
        $queryPaginationHandler = $this->changeRequestRepository->findAllChangeRequests($action, $startPage, $limit);

        return $queryPaginationHandler->getQueryResults();
    }
}