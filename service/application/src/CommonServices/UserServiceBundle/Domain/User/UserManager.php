<?php

namespace CommonServices\UserServiceBundle\Domain\User;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Domain\User\Manager\UserPreferencesManager;
use CommonServices\UserServiceBundle\Domain\User\Manager\UserSecurityManager;
use CommonServices\UserServiceBundle\Domain\User\Manager\UserAccountManager;
use CommonServices\UserServiceBundle\Domain\User\Manager\UserSettingsManager;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserServiceManager
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class UserManager
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

        $this->userSecurityManager   = new UserSecurityManager($this->user, $this->userRepository, $this->container);
        $this->userSettingsManager   = new UserSettingsManager($this->user, $this->userRepository, $this->container);
        $this->userAccountManager    = new UserAccountManager($this->user, $this->userRepository, $this->container);
        $this->userPreferncesManager = new UserPreferencesManager($this->user, $this->userRepository, $this->container);
    }

    /**
     * @return UserSecurityManager
     */
    public function getSecurity() : UserSecurityManager
    {
        return $this->userSecurityManager;
    }

    /**
     * @return UserSettingsManager
     */
    public function getSettings() :  UserSettingsManager
    {
        return $this->userSettingsManager;
    }

    /**
     * @return UserAccountManager
     */
    public function getAccount() :  UserAccountManager
    {
        return $this->userAccountManager;
    }

    /**
     * @return UserPreferencesManager
     */
    public function getPreferences() : UserPreferencesManager
    {
        return $this->userPreferncesManager;
    }
}