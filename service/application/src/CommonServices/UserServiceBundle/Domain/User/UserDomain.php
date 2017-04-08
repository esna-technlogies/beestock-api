<?php

namespace CommonServices\UserServiceBundle\Domain\User;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserDomain
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class UserDomain
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UserDomainService
     */
    private $userDomainService;

    /**
     * ItemService constructor.
     * @param ContainerInterface $container
     * @param UserRepository $userRepository
     */
    public function __construct(ContainerInterface $container, UserRepository $userRepository)
    {
        $this->container = $container;
        $this->userRepository = $userRepository;
        $this->userDomainService = new UserDomainService($container, $userRepository);
    }

    /**
     * @param User $user
     * @return UserManager
     */
    public function getUser(User $user) : UserManager
    {
        return new UserManager($user, $this->userRepository, $this->container);
    }

    /**
     * Check if user exist
     * @return UserRepository
     */
    public function getUserRepository() : UserRepository
    {
        return $this->userRepository;
    }

    /**
     * Check if user exist
     * @return UserDomainService
     */
    public function getDomainService() : UserDomainService
    {
        return $this->userDomainService;
    }

    /**
     * processPendingAccountsChanges upon destruction of domain
     */
    public function __destruct()
    {
        //$this->getDomainService()->processPendingAccountsChanges();
    }
}