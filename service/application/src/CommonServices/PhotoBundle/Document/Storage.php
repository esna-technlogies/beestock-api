<?php

namespace CommonServices\PhotoBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @package PhotoBundle\PhotoBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Storage
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $fileId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $bucketName;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $userId;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $fileExtensions = [];


    /**
     * @MongoDB\EmbedOne(targetDocument="CommonServices\PhotoBundle\Document\Thumbnails")
     */
    protected $sizes;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $originalFile;

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
    public function getBucketName()
    {
        return $this->bucketName;
    }

    /**
     * @param mixed $bucketName
     */
    public function setBucketName($bucketName)
    {
        $this->bucketName = $bucketName;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getFileExtensions()
    {
        return $this->fileExtensions;
    }

    /**
     * @param mixed $fileExtensions
     */
    public function setFileExtensions($fileExtensions)
    {
        $this->fileExtensions = $fileExtensions;
    }

    /**
     * @return Thumbnails
     */
    public function getSizes() : ?Thumbnails
    {
        return $this->sizes;
    }

    /**
     * @param Thumbnails $sizes
     */
    public function setSizes($sizes)
    {
        $this->sizes = $sizes;
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
}
