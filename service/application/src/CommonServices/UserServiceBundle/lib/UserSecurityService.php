<?php

namespace CommonServices\UserServiceBundle\lib;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserSecurityService
 * @package CommonServices\UserServiceBundle\lib
 */
class UserSecurityService
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
     * @param string $userName
     * @param string $password
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function authenticateUser(string $userName = '', string $password = '')
    {
        /** @var User $user */
        $user = $this->serviceContainer->get('user_service.core')->getUserByUserName($userName);

        if(!$this->hasValidCredentials($user, $password))
        {
            throw new InvalidArgumentException('Invalid Credentials');
        }

        // setting a temp username which is used for the login of this session
        $user->getAccessInfo()->setUsername($userName);

        return $this->serviceContainer
                    ->get('user_service.security.json_web_token_handler')
                    ->getJsonWebToken($user);
    }

    /**
     * @param User $user
     * @param string $password
     *
     * @return bool
     */
    public function hasValidCredentials(User $user, string $password) : bool
    {
        /** @var AccessInfo $accessInfo */
        $accessInfo = $user->getAccessInfo();

        $isValid = $this->serviceContainer
            ->get('security.password_encoder')
            ->isPasswordValid($accessInfo, $password);

        if (!$isValid) {
            return false;
        }

        return true;
    }
}
