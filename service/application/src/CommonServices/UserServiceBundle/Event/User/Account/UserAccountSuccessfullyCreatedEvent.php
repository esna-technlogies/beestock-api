<?php

namespace CommonServices\UserServiceBundle\Event\User\Account;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserCreatedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserAccountSuccessfullyCreatedEvent extends Event
{
    const NAME = 'user_account.created';

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