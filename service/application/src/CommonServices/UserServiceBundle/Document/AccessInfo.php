<?php

namespace CommonServices\UserServiceBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @package UserServiceBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class AccessInfo implements UserInterface
{
    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $password;

    /**
     * @var \DateTime $created
     * @MongoDB\Timestamp
     * @Gedmo\Timestampable(on="change", field={"password"})
     */
    protected $lastChange;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $salt;

    /**
     * @MongoDB\Field(type="collection")
     * @Assert\NotBlank()
     */
    protected $roles;

    /**
     * Set password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set lastChange
     *
     * @param \DateTime $lastChange
     * @return $this
     */
    public function setLastChange($lastChange)
    {
        $this->lastChange = $lastChange;
        return $this;
    }

    /**
     * Get lastChange
     *
     * @return \DateTime $lastChange
     */
    public function getLastChange()
    {
        return $this->lastChange;
    }

    /**
     * Set password salt
     *
     * @param string $salt
     * @return $this
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Get password salt
     *
     * @return string $salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set userRoles
     *
     * @param array $roles
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Get userRoles
     *
     * @return array $roles
     */
    public function getRoles()
    {
        return array($this->roles);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }
}
