<?php

namespace CommonServices\UserServiceBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package UserServiceBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class PhoneNumber
{
    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $countryCode;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $number;

    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string $countryCode
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set phoneNumber
     *
     * @param string $number
     * @return $this
     */
    public function setPhoneNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string $number
     */
    public function getPhoneNumber()
    {
        return $this->number;
    }
}
