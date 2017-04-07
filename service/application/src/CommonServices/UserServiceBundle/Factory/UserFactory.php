<?php

namespace CommonServices\UserServiceBundle\Factory;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Form\Processor\UserBasicInfoProcessor;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserFactory
 * @package CommonServices\UserServiceBundle\Factory
 */
class UserFactory
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->container = $container;
    }

    public function createUserFromBasicInfo(array $basicAccountInformation) : User
    {
        $userEntity = new User();

        $userProcessor = new UserBasicInfoProcessor($this->container->get('form.factory'));

        $user = $userProcessor->processForm($userEntity, $basicAccountInformation, true);

        $this->userRepository->save($user);

        return $user;
    }
}