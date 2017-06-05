<?php

namespace CommonServices\UserServiceBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package UserServiceBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class StorageBucket
{
    /**
     * @param mixed $bucketUrl
     */
    public function setBucketUrl($bucketUrl)
    {
        $this->bucketUrl = $bucketUrl;
    }
    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $bucketId;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */

    protected $bucketUrl;
    /**
     * @return mixed
     */
    public function getBucketId()
    {
        return $this->bucketId;
    }

    /**
     * @param mixed $bucketId
     */
    public function setBucketId($bucketId)
    {
        $this->bucketId = $bucketId;
    }

    /**
     * @return mixed
     */
    public function getBucketUrl()
    {
        return $this->bucketUrl;
    }
}