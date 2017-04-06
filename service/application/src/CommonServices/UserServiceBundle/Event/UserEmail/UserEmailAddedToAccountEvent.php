<?php

namespace CommonServices\UserServiceBundle\Event\UserEmail;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEmailAddedRequestedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserEmailAddedToAccountEvent extends Event
{
    const NAME = 'user_email.added';

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
    private $email;

    /**
     * UserEmailChangedEvent constructor
     * @param User $user
     * @param string $email
     */
    public function __construct(User $user, string $email)
    {
        $this->user = $user;

        $this->eventFiringTime = time();
        $this->email = $email;
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}