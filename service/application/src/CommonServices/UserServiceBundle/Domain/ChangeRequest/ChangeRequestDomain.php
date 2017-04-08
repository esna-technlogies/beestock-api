<?php

namespace CommonServices\UserServiceBundle\Domain\ChangeRequest;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
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
     * @return ChangeRequestManager
     */
    public function getChangeRequest(ChangeRequest $changeRequest) : ChangeRequestManager
    {
        return new ChangeRequestManager($this->container, $changeRequest, $this->changeRequestRepository);
    }

    /**
     * @return ChangeRequestSearchManager
     */
    public function getSearch()
    {
        return new ChangeRequestSearchManager($this->container, $this->changeRequestRepository);
    }

    /**
     * @return ChangeRequestService
     */
    public function getDomainService() : ChangeRequestService
    {
        return new ChangeRequestService($this->container, $this->changeRequestRepository);
    }
}
