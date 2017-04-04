<?php

namespace CommonServices\UserServiceBundle\Event;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEmailChangedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserEmailChangedEvent extends Event
{
    const NAME = 'user_email.changed';

    protected $eventFiringTime;

    /**
     * @var User
     */
    protected $user;

    /**
     * UserEmailChangedEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->eventFiringTime = time();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}