<?php

namespace CommonServices\UserServiceBundle\Domain\ChangeRequest\Manager;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Repository\ChangeRequestRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChangeRequestManager
 * @package CommonServices\UserServiceBundle\Domain\ChangeRequest\Manager
 */
class ChangeRequestManager
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ChangeRequest
     */
    private $changeRequest;
    /**
     * @var ChangeRequestRepository
     */
    private $changeRequestRepository;

    /**
     * ChangeRequestManager constructor.
     * @param ContainerInterface $container
     * @param ChangeRequest $changeRequest
     * @param ChangeRequestRepository $changeRequestRepository
     */
    public function __construct(ContainerInterface $container, ChangeRequest $changeRequest, ChangeRequestRepository $changeRequestRepository)
    {
        $this->container = $container;
        $this->changeRequest = $changeRequest;
        $this->changeRequestRepository = $changeRequestRepository;
    }

    /**
     * @param $verificationCode
     */
    public function executeChange($verificationCode)
    {
        $this->verifyChangeRequest($verificationCode);

    }

    public function verifyChangeRequest($verificationCode)
    {
        
    }
}