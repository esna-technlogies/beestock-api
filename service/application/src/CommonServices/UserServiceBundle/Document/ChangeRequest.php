<?php

namespace CommonServices\UserServiceBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude as Exclude;
use Gedmo\Mapping\Annotation as Gedmo;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @package UserServiceBundle\Document
 *
 * @MongoDB\Document(
 *     repositoryClass="CommonServices\UserServiceBundle\Repository\ChangeRequestRepository"
 * )
 *
 */
class ChangeRequest
{
    /**
     * @MongoDB\Id(strategy="AUTO", type="string")
     *
     * @Exclude
     */
    protected $id;

    /**
     * @MongoDB\UniqueIndex
     *
     * @MongoDB\Field(type="string")
     */
    protected $uuid;

    /**
     * @Exclude
     *
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $user;

    /**
     * @Exclude
     *
     * @var \DateTime $created
     * @MongoDB\Timestamp
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @Exclude
     *
     * @var \DateTime $lastChange
     * @MongoDB\Timestamp
     * @Gedmo\Timestampable(on="update")
     */
    protected $lastChange;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     *
     * @Exclude
     */
    protected $changeProcessorName;

    /**
     * @Exclude
     *
     * @MongoDB\Field(type="integer")
     * @Assert\NotBlank()
     */
    protected $eventFiringTime;

    /**
     * @Exclude
     *
     * @MongoDB\Field(type="integer")
     * @Assert\NotBlank()
     */
    protected $eventLifeTime;

    /**
     * @Exclude
     *
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $eventName;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     *
     * @Exclude
     */
    protected $oldValue;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     *
     * @Exclude
     */
    protected $newValue;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     *
     * @Exclude
     */
    protected $verificationCode;

    /**
     * @MongoDB\Field(type="bool")
     * @Assert\NotBlank()
     *
     * @Exclude
     */
    protected $userNotified  = false;

    /**
     * @return mixed
     */
    public function getEventLifeTime()
    {
        return $this->eventLifeTime;
    }

    /**
     * @param mixed $eventLifeTime
     */
    public function setEventLifeTime($eventLifeTime)
    {
        $this->eventLifeTime = $eventLifeTime;
    }

    /**
     * @return mixed
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * @param mixed $verificationCode
     */
    public function setVerificationCode($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * @return mixed
     */
    public function getChangeProcessorName()
    {
        return $this->changeProcessorName;
    }

    /**
     * @param mixed $changeProcessorName
     */
    public function setChangeProcessorName($changeProcessorName)
    {
        $this->changeProcessorName = $changeProcessorName;
    }

    /**
     * @return mixed
     */
    public function getEventFiringTime()
    {
        return $this->eventFiringTime;
    }

    /**
     * @param mixed $eventFiringTime
     */
    public function setEventFiringTime($eventFiringTime)
    {
        $this->eventFiringTime = $eventFiringTime;
    }

    /**
     * @return mixed
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param mixed $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @param mixed $oldValue
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;
    }

    /**
     * @return mixed
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * @param mixed $newValue
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId() : string
    {
        return (string) $this->id;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime $created
     */
    public function getCreated()
    {
        return $this->created;
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
     * Set UserNotified
     *
     * @param boolean $userNotified
     * @return $this
     */
    public function setUserNotified($userNotified)
    {
        $this->userNotified = $userNotified;
        return $this;
    }

    /**
     * Get UserNotified
     *
     * @return boolean $userNotified
     */
    public function getUserNotified()
    {
        return $this->userNotified;
    }

    /**
     * Set setUser
     *
     * @param string $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get lastChange
     *
     * @return string $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
