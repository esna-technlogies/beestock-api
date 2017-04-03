<?php

namespace CommonServices\UserServiceBundle\Event;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PasswordRetrievalRequestedEvent
 * @package CommonServices\UserServiceBundle\Event
 */
class UserPasswordRetrievalRequestedEvent extends Event
{
    const NAME = 'user_password_retrieval.requested';

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