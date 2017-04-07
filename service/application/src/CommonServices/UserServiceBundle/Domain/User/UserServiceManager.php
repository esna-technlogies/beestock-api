<?php

namespace CommonServices\UserServiceBundle\Domain\User;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Domain\User\Manager\UserAccountManager;
use CommonServices\UserServiceBundle\Domain\User\Manager\UserPreferencesManager;
use CommonServices\UserServiceBundle\Domain\User\Manager\UserSecurityManager;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserServiceManager
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class UserServiceManager
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserAccountService constructor.
     * @param User $user
     * @param UserRepository $userRepository
     * @param ContainerInterface $container
     */
    public function __construct(User $user, UserRepository $userRepository, ContainerInterface $container)
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
        $this->container = $container;
    }

    /**
     * @return UserSecurityManager
     */
    public function getSecurity()
    {
        return new UserSecurityManager($this->user, $this->userRepository, $this->container);
    }

    /**
     * @return UserAccountManager
     */
    public function getAccount()
    {
        return new UserAccountManager($this->user, $this->userRepository, $this->container);
    }

    /**
     * @return UserPreferencesManager
     */
    public function getPreferences()
    {
        return new UserPreferencesManager($this->user, $this->userRepository, $this->container);
    }
}