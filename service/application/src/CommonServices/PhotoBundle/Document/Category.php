<?php

namespace CommonServices\UserServiceBundle\Document;

use Hateoas\Configuration\Annotation as Hateoas;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Hateoas\Relation(
 *      "photo"
 * )
 *
 * @package UserServiceBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Category
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
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $internationalNumber;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $nationalNumber;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $internationalNumberForCalling;

    /**
     * @return string
     */
    public function getInternationalNumberForCalling()
    {
        return $this->internationalNumberForCalling;
    }

    /**
     * @param string $internationalNumberForCalling
     */
    public function setInternationalNumberForCalling($internationalNumberForCalling)
    {
        $this->internationalNumberForCalling = $internationalNumberForCalling;
    }

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
     * Set number
     *
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Get number
     *
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getNationalNumber()
    {
        return $this->nationalNumber;
    }

    /**
     * @param mixed $nationalNumber
     */
    public function setNationalNumber($nationalNumber)
    {
        $this->nationalNumber = $nationalNumber;
    }

    /**
     * Set internationalMobileNumber
     *
     * @param string $internationalNumber
     * @return $this
     */
    public function setInternationalNumber($internationalNumber)
    {
        $this->internationalNumber = $internationalNumber;
        return $this;
    }

    /**
     * Get internationalMobileNumber
     *
     * @return string $internationalMobileNumber
     */
    public function getInternationalNumber()
    {
        return $this->internationalNumber;
    }
}
