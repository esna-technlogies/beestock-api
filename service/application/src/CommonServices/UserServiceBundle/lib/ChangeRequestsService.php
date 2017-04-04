<?php

namespace CommonServices\UserServiceBundle\lib;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\lib\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChangeRequestsService
 * @package CommonServices\UserServiceBundle\lib
 */
class ChangeRequestsService
{
    /**
     * @var ChangeRequestRepository
     */
    private $changeRequestRepository;

    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

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
     * @return ChangeRequest
     */
    public function createChangeRequest() : ChangeRequest
    {
        return new ChangeRequest();
    }

    /**
     * @param ChangeRequest $changeRequest
     */
    public function updateChangeRequest(ChangeRequest $changeRequest)
    {
        $this->changeRequestRepository->save($changeRequest);
    }

    /**
     * @param ChangeRequest $changeRequest
     */
    public function deleteChangeRequest(ChangeRequest $changeRequest)
    {
        $this->changeRequestRepository->delete($changeRequest);
    }

    /**
     * @param int $limit
     * @param string $action
     * @return array
     */
    public function getMostRecentRequests(string $action, int $limit =10)
    {
        $queryPaginationHandler = new QueryPaginationHandler(1, $limit);

        $this->changeRequestRepository->findAllChangeRequests($queryPaginationHandler, $action);

        return $queryPaginationHandler->getQueryResults();
    }
}







