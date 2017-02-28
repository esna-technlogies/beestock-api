<?php

namespace CommonServices\UserServiceBundle\lib;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Form\Type\UserType;
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
        $user  = new User();
        $form =  $this->serviceContainer
                    ->get('form.factory')
                    ->createBuilder(UserType::class, $user)
                    ->getForm();

        $form->submit($userData);

        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form);
        }

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