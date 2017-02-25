<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 25/02/2017
 * Time: 9:56 AM
 */

namespace ImageStock\UserServiceBundle\lib;

use ImageStock\UserServiceBundle\Document\User;
use ImageStock\UserServiceBundle\Exception\InvalidFormException;
use ImageStock\UserServiceBundle\Form\Type\UserType;
use ImageStock\UserServiceBundle\Repository\UserRepository;
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
    public function addNewUser(array $userData){

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
    public function getAllUsers(){

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