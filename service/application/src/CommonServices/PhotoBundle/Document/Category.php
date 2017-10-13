<?php

namespace CommonServices\PhotoBundle\Document;

use Hateoas\Configuration\Annotation as Hateoas;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Exclude;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @package PhotoBundle\Document
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "photo_service_get_category",
 *          parameters = { "uuid" = "expr(object.getUuid())" }
 *     )
 * )
 *
 * @MongoDB\Document(
 *     collection="categories",
 *     repositoryClass="CommonServices\PhotoBundle\Repository\CategoryRepository",
 *     indexes={
 *         @MongoDB\Index(keys={"title":"asc"})
 *     }
 * )
 */
class Category
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
     *
     * @Exclude
     */
    protected $created;

    /**
     * @var \DateTime $lastChange
     * @MongoDB\Timestamp
     * @Gedmo\Timestampable(on="update")
     *
     * @Exclude
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
     * @return mixed
     *
     * @Exclude
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getLastChange()
    {
        return $this->lastChange;
    }

    /**
     * @param mixed $lastChange
     */
    public function setLastChange($lastChange)
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
     * Photo constructor.
     */
    public function __construct()
    {
        $this->setUuid(Uuid::uuid4()->toString());
    }

}
