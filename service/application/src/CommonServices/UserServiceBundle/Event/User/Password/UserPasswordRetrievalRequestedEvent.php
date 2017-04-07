<?php

namespace CommonServices\UserServiceBundle\Event\User\Password;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PasswordRetrievalRequestedEvent
 * @package CommonServices\UserServiceBundle\Event
 */
class UserPasswordRetrievalRequestedEvent extends Event
{
    const NAME = 'user_password_retrieval.requested';

    /**
     * @var string
     */
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

        $this->eventFiringTime = time();
    }

    /**
     * @return User
     */
    public function getUserDocument()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getEventFiringTime()
    {
        return $this->eventFiringTime;
    }
}