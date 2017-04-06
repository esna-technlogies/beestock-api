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
     * @param User $user
     * @return UserServiceManager
     */
    public function getUser(User $user) : UserServiceManager
    {
        return new UserServiceManager($user, $this->userRepository, $this->container);
    }

    /**
     * Check if user exist
     * @return UserRepository
     */
    public function getUserRepository() : UserRepository
    {
        return $this->userRepository;
    }
}