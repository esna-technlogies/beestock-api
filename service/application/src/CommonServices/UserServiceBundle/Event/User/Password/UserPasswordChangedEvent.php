<?php

namespace CommonServices\UserServiceBundle\Event\User\Password;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserPasswordChangedEvent
 * @package CommonServices\UserServiceBundle\Event\Event
 */
class UserPasswordChangedEvent extends Event
{
    const NAME = 'user_password.changed';

    protected $eventFiringTime;

    /**
     * @var AccessInfo
     */
    protected $userAccessInfo;

    /**
     * PasswordChangedEvent constructor.
     *
     * @param AccessInfo $userAccessInfo
     */
    public function __construct(AccessInfo $userAccessInfo)
    {
        $this->userAccessInfo = $userAccessInfo;

        $this->eventFiringTime = new \DateTime();
    }

    /**
     * @return AccessInfo
     */
    public function getUserAccessInfo()
    {
        return $this->userAccessInfo;
    }
}