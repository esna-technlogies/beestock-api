<?php

namespace CommonServices\UserServiceBundle\Event;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserCreatedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserCreatedEvent extends Event
{
    const NAME = 'user_created.created';

    protected $eventFiringTime;

    /**
     * @var User
     */
    protected $user;

    /**
     * PasswordChangedEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->eventFiringTime = new \DateTime();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}