<?php

namespace CommonServices\UserServiceBundle\Event\User\Account;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserAccountActivatedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserAccountActivatedEvent extends Event
{
    const NAME = 'user_account.activated';

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