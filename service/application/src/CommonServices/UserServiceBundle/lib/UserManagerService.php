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
use CommonServices\UserServiceBundle\Exception\NotFoundException;

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
     * @return User
     */
    public function createUser(User $user, array $userData) : User
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
     * @throws InvalidFormException
     *
     * @return User $user
     */
    public function saveUser(User $user)
    {
        $this->userRepository->save($user);
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
        return $this->userRepository->findOneByMobileNumber($internationalMobileNumber);
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
     * @return User
     */
    public function createNewUser()
    {
        $user = new User();
        $user->setAccessInfo(new AccessInfo());

        return $user;
    }


    /**
     * @param string $userName
     *
     * @throws NotFoundException
     * @return object | null
     */
    public function getUserByUserName(string $userName)
    {
        $user = null;
        if(filter_var($userName, FILTER_VALIDATE_EMAIL))
        {
            $email = $userName;
            $user = $this->userRepository->findOneByEmail($email);
        }

        if(preg_match('/^[0-9]+$/', $userName))
        {
            $mobileNumber = preg_replace('/[^0-9]/', '', $userName);
            $user = $this->userRepository->findOneByMobileNumber($mobileNumber, $mobileNumber);
        }

        if(is_null($user)){
            throw new NotFoundException('User not found', 404);
        }

        return $user;
    }


    /**
     * Check if user exist
     * @param string $uuid
     * @return object | null
     */
    public function getUserByUuid($uuid)
    {
        return $this->userRepository->findByUUID($uuid);
    }

    /**
     * Check if user exist
     * @param string $uuid
     * @return bool
     */
    public function has($uuid)
    {
        return $this->userRepository->find($uuid) ? true : false;
    }
}