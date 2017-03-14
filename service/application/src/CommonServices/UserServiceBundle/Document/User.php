<?php

namespace CommonServices\UserServiceBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Exclude as Exclude;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @package UserServiceBundle\Document
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "user_service_get_user",
 *          parameters = { "uuid" = "expr(object.getUuid())" }
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "facebookAccount",
 *      href = @Hateoas\Route(
 *          "user_service_get_user_facebook_account",
 *          parameters = { "uuid" = "expr(object.getUuid())" }
 *     ),
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object === null)")
 * )
 *
 * @Hateoas\Relation(
 *     "googleAccount",
 *      href = @Hateoas\Route(
 *          "user_service_get_user_google_account",
 *          parameters = { "uuid" = "expr(object.getUuid())" }
 *     ),
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object === null)")
 * )
 *
 * @MongoDB\Document(
 *     collection="users",
 *     repositoryClass="CommonServices\UserServiceBundle\Repository\UserRepository",
 *     indexes={
 *         @MongoDB\Index(keys={"firstName":"asc", "lastName":"asc", "fullName":"asc"})
 *     }
 * )
 */
class User
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
     * @var \DateTime $created
     * @MongoDB\Timestamp
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @var \DateTime $lastChange
     * @MongoDB\Timestamp
     * @Gedmo\Timestampable(on="update")
     */
    protected $lastChange;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $lastName;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $fullName;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $country;

    /**
     * @MongoDB\Field(type="boolean")
     * @Assert\NotBlank()
     */
    protected $termsAccepted;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $language;

    /**
     * @MongoDB\EmbedOne(targetDocument="CommonServices\UserServiceBundle\Document\PhoneNumber")
     */
    protected $mobileNumber;

    /**
     * @MongoDB\EmbedOne(targetDocument="CommonServices\UserServiceBundle\Document\AccessInfo")
     *
     * @Exclude
     */
    protected $accessInfo;

    /**
     * @MongoDB\EmbedOne(targetDocument="CommonServices\UserServiceBundle\Document\FacebookAccount")
     *
     * @Exclude
     */
    protected $facebookAccount;

    /**
     * @MongoDB\EmbedOne(targetDocument="CommonServices\UserServiceBundle\Document\GoogleAccount")
     *
     * @Exclude
     */
    protected $googleAccount;

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
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set termsAccepted
     *
     * @param string $termsAccepted
     * @return $this
     */
    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = $termsAccepted;
        return $this;
    }

    /**
     * Get termsAccepted
     *
     * @return string $termsAccepted
     */
    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * Get fullName
     *
     * @return string $fullName
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set accessInfo
     *
     * @param $accessInfo
     * @return $this
     */
    public function setAccessInfo($accessInfo)
    {
        $this->accessInfo = $accessInfo;
        return $this;
    }

    /**
     * Get accessInfo
     *
     * @return AccessInfo
     */
    public function getAccessInfo()
    {
        return $this->accessInfo;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     * @return $this
     */
    public function setUuid($uuid)
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
     * Set country
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set facebookAccount
     *
     * @param FacebookAccount $facebookAccount
     * @return $this
     */
    public function setFacebookAccount(FacebookAccount $facebookAccount)
    {
        $this->facebookAccount = $facebookAccount;
        return $this;
    }

    /**
     * Get facebookAccount
     *
     * @return FacebookAccount
     */
    public function getFacebookAccount()
    {
        return $this->facebookAccount;
    }

    /**
     * Set googleAccount
     *
     * @param GoogleAccount $googleAccount
     * @return $this
     */
    public function setGoogleAccount(GoogleAccount $googleAccount)
    {
        $this->googleAccount = $googleAccount;
        return $this;
    }

    /**
     * Get googleAccount
     *
     * @return GoogleAccount
     */
    public function getGoogleAccount()
    {
        return $this->googleAccount;
    }

    /**
     * Set mobile number
     *
     * @param PhoneNumber $mobileNumber
     * @return $this
     */
    public function setMobileNumber(PhoneNumber $mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
        return $this;
    }

    /**
     * Get mobile number
     *
     * @return PhoneNumber
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language
     *
     * @return string $language
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
