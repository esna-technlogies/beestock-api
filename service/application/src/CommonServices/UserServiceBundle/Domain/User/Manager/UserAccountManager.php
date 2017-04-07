<?php

namespace CommonServices\UserServiceBundle\Domain\User\Manager;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Form\Processor\UserBasicInfoProcessor;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use CommonServices\UserServiceBundle\Utility\MobileNumberFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserAccountManager
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

    /**
     * @param array $userBasicInformation
     * @throws InvalidFormException
     *
     * @return User $user
     */
    public function updateAccountBasicInformation(array $userBasicInformation)
    {
        $userProcessor = new UserBasicInfoProcessor($this->container->get('form.factory'));

        $user = $userProcessor->processForm($this->user, $userBasicInformation, false);

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param null $time
     */
    public function setLastPasswordRetrievalRequest($time = null)
    {
        if(!isset($time))
        {
            $time = time();
        }
        $this->user->getAccessInfo()->setLastPasswordRetrievalRequest($time);
        $this->userRepository->save($this->user);
    }

    /**
     * @param ChangeRequest $changeRequest
     */
    public function issueAccountChangeRequest(ChangeRequest $changeRequest)
    {
        $email_change = 'email_change';
        $this->container
            ->get('user_service.amqb_producer.'.$email_change.'_producer')
            ->publish(serialize($changeRequest)
        );
    }

    /**
     * @param string $mobileNumber
     * @param string $countryCode
     */
    public function setMobileNumberAlternatives(string $mobileNumber, string $countryCode)
    {
        $mobileNumberFormatter = new MobileNumberFormatter($mobileNumber, $countryCode);

        $mobileNumberDocument = $this->user->getMobileNumber();

        $mobileNumberDocument->setNationalNumber($mobileNumberFormatter->getNationalMobileNumber());

        $mobileNumberDocument->setInternationalNumber($mobileNumberFormatter->getInternationalMobileNumber());

        $mobileNumberDocument->setInternationalNumberForCalling($mobileNumberFormatter->getInternationalMobileNumberForCalling());
    }

    /**
     * @return User
     */
    public function getUserEntity()
    {
        return $this->user;
    }
}