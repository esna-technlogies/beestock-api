<?php

namespace CommonServices\UserServiceBundle\lib;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Processor\UserProcessor;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * ItemService constructor.
     * @param ContainerInterface $serviceContainer
     * @param UserRepository $userRepository
     */
    public function __construct(ContainerInterface $serviceContainer, UserRepository $userRepository)
    {
        $this->serviceContainer = $serviceContainer;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $userData
     * @throws InvalidFormException
     *
     * @return User $user
     */
    public function addNewUser(array $userData)
    {
        $userProcessor = new UserProcessor($this->serviceContainer->get('form.factory'));
        $user = $userProcessor->processForm(new User(), $userData);

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @return array
     */
    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param User $user
     */
    public function deleteUser($user){

    }

    /**
     * @param User $user
     */
    public function changeUserPassword($user){

    }

    /**
     * Check if user exist
     * @param string $userId
     * @return bool
     */
    public function has($userId)
    {
        return $this->userRepository->find($userId) ? true : false;
    }
}