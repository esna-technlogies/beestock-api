<?php

namespace CommonServices\UserServiceBundle\Event\User\Name;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserNameChangedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserNameChangedEvent extends Event
{
    const NAME = 'user_name.changed';

    protected $eventFiringTime;

    /**
     * @var User
     */
    protected $user;

    /**
     * UserNameChangedEvent constructor.
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