<?php

namespace CommonServices\UserServiceBundle\Domain\User\Manager;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use CommonServices\UserServiceBundle\Utility\Security\RandomCodeGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserSettingsManager
 * @package CommonServices\UserServiceBundle\Domain\User\Manager
 */
class UserAccountManager
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

    public function __construct(User $user, UserRepository $userRepository, ContainerInterface $container)
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
        $this->container = $container;
    }

    /**
     * @param string $eventName
     * @param int $requestLifeTime
     * @param $oldValue
     * @param $newValue
     *
     * @return ChangeRequest
     */
    public function issueAccountChangeRequest(string $eventName, int $requestLifeTime, $oldValue = null, $newValue = null) : ChangeRequest
    {
        $changeRequestService = $this->container->get('user_service.change_request_domain');
        $verificationCode = RandomCodeGenerator::generateRandomVerificationString(6);

        $accountChange = $changeRequestService->getDomainService()->createPendingChangeRequest(
            $this->user,
            $verificationCode,
            $eventName,
            $requestLifeTime,
            $oldValue,
            $newValue,
            'user_account_change'
        );

        $this->container->get('user_service.user_domain')->getDomainService()->createPendingAccountsChange(clone $accountChange);

        return $accountChange;
    }

    /**
     * Deletes user account
     */
    public function deleteAccount()
    {
        $this->userRepository->delete($this->user);
    }

    /**
     * Deletes user account
     */
    public function suspendAccount()
    {
        $this->userRepository->softDelete($this->user);
    }
}
