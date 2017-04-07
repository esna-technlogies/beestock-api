<?php

namespace CommonServices\UserServiceBundle\Domain\User\Manager;

use CommonServices\UserServiceBundle\Document\User;

/**
 * Class UserSettingsManager
 * @package CommonServices\UserServiceBundle\Domain\User\Manager
 */
class UserSettingsManager
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

}