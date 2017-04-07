<?php

namespace CommonServices\UserServiceBundle\Event\UserEmail;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEmailChangedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserEmailChangeRequestedEvent extends Event
{
    const NAME = 'user_email.change_requested';

    /**
     * @var int
     */
    protected $eventFiringTime;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    private $oldValue;

    /**
     * @var string
     */
    private $newValue;

    /**
     * UserEmailChangedEvent constructor
     * @param User $user
     * @param string $oldValue
     * @param string $newValue
     */
    public function __construct(User $user, string $newValue, $oldValue ='')
    {
        $this->user = $user;

        $this->eventFiringTime = time();
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }

    /**
     * @return int
     */
    public function getEventFiringTime(): int
    {
        return $this->eventFiringTime;
    }

    /**
     * @return string
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @return string
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}