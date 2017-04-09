<?php

namespace CommonServices\UserServiceBundle\Domain\User\Manager;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Form\Processor\UserBasicInfoProcessor;
use CommonServices\UserServiceBundle\Repository\UserRepository;
use CommonServices\UserServiceBundle\Utility\Formatter\MobileNumberFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserSettingsManager
 * @package CommonServices\UserServiceBundle\Domain\User\Manager
 */
class UserSettingsManager
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
     * @param array $userBasicInformation
     * @throws InvalidFormException
     *
     * @return User $user
     */
    public function updateAccountBasicSettings(array $userBasicInformation)
    {
        $userProcessor = new UserBasicInfoProcessor($this->container->get('form.factory'));

        $user = $userProcessor->processForm($this->user, $userBasicInformation, false);

        $this->userRepository->save($user);

        return $user;
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
}
