<?php

namespace CommonServices\PhotoBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Exclude as Exclude;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @package PhotoBundle\Document
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "photo_service_get_photo",
 *          parameters = { "uuid" = "expr(object.getUuid())" }
 *     )
 * )
 *
 * @MongoDB\Document(
 *     collection="photos",
 *     repositoryClass="CommonServices\PhotoBundle\Repository\PhotoRepository",
 *     indexes={
 *         @MongoDB\Index(keys={"title":"asc"})
 *     }
 * )
 */
class Photo
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
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $user;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $category;

    /**
     * @MongoDB\Field(type="collection")
     * @Assert\NotBlank()
     */
    protected $keywords = [];

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $originalFile;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $approved  = false;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $adminComment;

    /**
     * @MongoDB\EmbedOne(targetDocument="CommonServices\PhotoBundle\Document\Storage")
     */
    protected $fileStorage;

    /**
     * @return string
     */
    public function getOriginalFile()
    {
        return $this->originalFile;
    }

    /**
     * @param mixed $originalFile
     */
    public function setOriginalFile($originalFile)
    {
        $this->originalFile = $originalFile;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getApproved() : bool
    {
        return $this->approved;
    }

    /**
     * @param mixed $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return mixed
     */
    public function getAdminComment() : string
    {
        return $this->adminComment;
    }

    /**
     * @param mixed $adminComment
     */
    public function setAdminComment($adminComment)
    {
        $this->adminComment = $adminComment;
    }


    /**
     * @return int
     */
    public function getCreated(): int
    {
        return $this->created->getTimestamp();
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getLastChange(): int
    {
        return $this->lastChange->getTimestamp();
    }

    /**
     * @param \DateTime $lastChange
     */
    public function setLastChange(\DateTime $lastChange)
    {
        $this->lastChange = $lastChange;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = explode(",", $keywords);
    }

    /**
     * @return Storage
     */
    public function getFileStorage() : Storage
    {
        return $this->fileStorage;
    }

    /**
     * @param mixed $fileStorage
     */
    public function setFileStorage(Storage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    /**
     * Photo constructor.
     */
    public function __construct()
    {
        $this->setUuid(Uuid::uuid4()->toString());
    }
}
