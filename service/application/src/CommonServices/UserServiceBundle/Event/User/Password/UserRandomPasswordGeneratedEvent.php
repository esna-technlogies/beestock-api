<?php

namespace CommonServices\UserServiceBundle\Event\User\Password;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserRandomPasswordGeneratedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserRandomPasswordGeneratedEvent extends Event
{
    const NAME = 'user_password.randomly_generated';

    protected $eventFiringTime;

    /**
     * @var AccessInfo
     */
    protected $userAccessInfo;

    /**
     * @var User
     */
    protected $user;

    /**
     * UserRandomPasswordGeneratedEvent constructor.
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