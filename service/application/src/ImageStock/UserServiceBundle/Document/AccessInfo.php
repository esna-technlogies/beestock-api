<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 23/02/2017
 * Time: 3:16 PM
 */

namespace ImageStock\UserServiceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @package UserServiceBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class AccessInfo
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
}
