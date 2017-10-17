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
 *          "photo_service_create_new_storage_file",
 *          parameters = { "uuid" = "expr(object.getUuid())" }
 *     )
 * )
 *
 * @MongoDB\Document(
 *     collection="fileStorage",
 *     repositoryClass="CommonServices\PhotoBundle\Repository\FileStorageRepository",
 *     indexes={
 *         @MongoDB\Index(keys={"fileId":"asc"})
 *     }
 * )
 */
class FileStorage
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
    protected $fileId;

    /**
     * @MongoDB\Field(type="collection")
     * @Assert\NotBlank()
     */
    protected $extensions = [];

    /**
     * @MongoDB\Field(type="collection")
     * @Assert\NotBlank()
     */
    protected $sizes = [];

    /**
     * @MongoDB\Field(type="string")
     */
    protected $user;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $originalFile;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $bucket;


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
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getLastChange(): \DateTime
    {
        return $this->lastChange;
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
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * @param mixed $fileId
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * @return mixed
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @param mixed $sizes
     */
    public function setSizes($sizes)
    {
        $this->sizes = explode(",", $sizes);
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
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param mixed $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * Photo constructor.
     */
    public function __construct()
    {
        $this->setUuid(Uuid::uuid4()->toString());
    }
}
