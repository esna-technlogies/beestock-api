<?php

namespace CommonServices\UserServiceBundle\Event\User\MobileNumber;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserMobileNumberChangedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserMobileNumberChangedEvent extends Event
{
    const NAME = 'user_mobile_number.changed';

    protected $eventFiringTime;

    /**
     * @var PhoneNumber
     */
    protected $mobileNumber;
    /**
     * @var User
     */
    private $user;
    /**
     * @var PhoneNumber
     */
    private $phoneNumber;

    /**
     * PasswordChangedEvent constructor.
     * @param PhoneNumber $phoneNumber
     */
    public function __construct(PhoneNumber $phoneNumber)
    {
        $this->eventFiringTime = new \DateTime();
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return PhoneNumber
     */
    public function getMobileNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}