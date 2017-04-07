<?php

namespace CommonServices\UserServiceBundle\Event\User\MobileNumber;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserMobileNumberChangeRequestedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserMobileNumberChangeRequestedEvent extends Event
{
    const NAME = 'user_mobile_number.change_requested';

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
     * PasswordChangedEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->eventFiringTime = new \DateTime();
    }

    /**
     * @return PhoneNumber
     */
    public function getMobileNumber()
    {
        return $this->user->getMobileNumber();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}