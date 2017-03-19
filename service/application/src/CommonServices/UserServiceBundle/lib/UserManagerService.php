<?php

namespace CommonServices\UserServiceBundle\lib;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\FacebookAccount;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Form\Processor\UserProcessor;
use CommonServices\UserServiceBundle\lib\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use CommonServices\UserServiceBundle\Repository\UserRepository;
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
    public function createUser(User $user, array $userData)
    {
        $user = $this->mapUserData($user, $userData);

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
    public function updateUser(User $user, array $userData)
    {
        $user = $this->mapUserData($user, $userData, false);

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param User $user
     * @param array $userData
     * @param boolean $clearMissing
     * @throws InvalidFormException
     *
     * @return User $user
     */
    private function mapUserData(User $user, array $userData, $clearMissing = true)
    {
        $userProcessor = new UserProcessor($this->serviceContainer->get('form.factory'));

        return $userProcessor->processForm($user, $userData, $clearMissing);
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
     * @param int $startPage
     * @param int $resultsPerPage
     * @return mixed
     */
    public function getAllUsers(int $startPage, int $resultsPerPage)
    {
        $queryPaginationHandler = new QueryPaginationHandler($startPage, $resultsPerPage);

        return $this->userRepository->findAllUsers($queryPaginationHandler);
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user)
    {
        $this->userRepository->delete($user);
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
     * Check if user exist
     * @param string $userId
     * @return bool
     */
    public function has($userId)
    {
        return $this->userRepository->find($userId) ? true : false;
    }
}