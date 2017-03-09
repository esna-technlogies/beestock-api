<?php

namespace CommonServices\UserServiceBundle\lib;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\FacebookAccount;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Form\Processor\UserProcessor;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Documents\CustomRepository\Document;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserManagerService
 * @package CommonServices\UserServiceBundle\lib
 */
class UserManagerService
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
     * @param User $user
     * @param array $userData
     * @throws InvalidFormException
     *
     * @return User $user
     */
    public function addNewUser(User $user, array $userData)
    {
        $user = $this->mapUserData($user, $userData);

        /** @var AccessInfo $accessInfo */
        $accessInfo   = $user->getAccessInfo();
        $userPassword = $accessInfo->getPassword();

        $encoder = $this->serviceContainer->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($accessInfo, $userPassword);

        $accessInfo->setPassword($encodedPassword);

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param User $user
     * @param array $userData
     * @throws InvalidFormException
     *
     * @return User $user
     */
    private function mapUserData(User $user, array $userData)
    {
        $userProcessor = new UserProcessor($this->serviceContainer->get('form.factory'));

        return $userProcessor->processForm($user, $userData);
    }

    /**
     * @param string $email
     * @return object
     */
    public function getUserByEmail(string $email)
    {
        return $this->userRepository->findOneBy(["email"=> $email]);
    }

    /**
     * @param string $internationalMobileNumber
     * @return object
     */
    public function getUserByMobileNumber(string $internationalMobileNumber)
    {
        return $this->userRepository->findOneBy(["mobileNumber.internationalNumber" => $internationalMobileNumber]);
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
     * @return User
     */
    public function createNewUser()
    {
        $user = new User();

        $user->setAccessInfo(new AccessInfo());
        $user->setFacebookAccount(new FacebookAccount());
        $user->setFacebookAccount(new FacebookAccount());

        return $user;
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $this->userRepository->save($user);
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