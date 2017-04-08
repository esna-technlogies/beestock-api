<?php

namespace CommonServices\UserServiceBundle\Domain\User;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserDomainService
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class UserDomainService
{
    public static $pendingAccountsChanges = [];

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ItemService constructor.
     * @param ContainerInterface $container
     * @param UserRepository $userRepository
     */
    public function __construct(ContainerInterface $container, UserRepository $userRepository)
    {
        $this->container = $container;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $basicAccountInformation
     * @throws InvalidFormException
     *
     * @return User
     */
    public function createUserAccount(array $basicAccountInformation) : User
    {
        $userFactory = $this->container->get('user_service.factory.user_factory');

        return $userFactory->createUserFromBasicInfo($basicAccountInformation);
    }

    /**
     * @param ChangeRequest $changeRequest
     */
    public function createPendingAccountsChange(ChangeRequest $changeRequest)
    {
        static::$pendingAccountsChanges[] = $changeRequest;
    }

    /**
     * processPendingAccountsChanges
     */
    public function processPendingAccountsChanges()
    {
        $changeRequestService = $this->container->get('user_service.change_request_domain');
        $changeRequestService->getDomainService()->processPendingRequests(static::$pendingAccountsChanges);
        static::$pendingAccountsChanges = [];
    }
}