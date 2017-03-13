<?php

namespace CommonServices\UserServiceBundle\Event;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
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
     * PasswordChangedEvent constructor.
     *
     * @param PhoneNumber $mobileNumber
     */
    public function __construct(PhoneNumber $mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;

        $this->eventFiringTime = new \DateTime();
    }

    /**
     * @return PhoneNumber
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }
}