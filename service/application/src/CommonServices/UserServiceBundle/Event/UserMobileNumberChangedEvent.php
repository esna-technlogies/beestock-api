<?php

namespace CommonServices\UserServiceBundle\Event;

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