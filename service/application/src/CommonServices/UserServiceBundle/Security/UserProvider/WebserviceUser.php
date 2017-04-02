<?php

namespace CommonServices\UserServiceBundle\Security\UserProvider;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * Class WebserviceUser
 * @package CommonServices\UserServiceBundle\Security\User
 */
class WebserviceUser implements UserInterface, EquatableInterface
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * WebserviceUser constructor.
     * @param string $email
     * @param string $uuid
     * @param string $password
     * @param string $salt
     * @param array $roles
     */
    public function __construct(string $email, string $uuid,string $password, string $salt, array $roles)
    {
        $this->uuid = $uuid;
        $this->email = $email;
        $this->username = $email;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
    }

    /**
     * @inheritdoc
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->uuid !== $user->getUuid()) {
            return false;
        }

        return true;
    }
}
