<?php

namespace CommonServices\PhotoBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @package PhotoBundle\PhotoBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Thumbnails
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $size_250;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $size_500;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $size_750;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $size_1000;

    /**
     * @return mixed
     */
    public function getSize250()
    {
        return $this->size_250;
    }

    /**
     * @param mixed $size_250
     */
    public function setSize250($size_250)
    {
        $this->size_250 = $size_250;
    }

    /**
     * @return mixed
     */
    public function getSize500()
    {
        return $this->size_500;
    }

    /**
     * @param mixed $size_500
     */
    public function setSize500($size_500)
    {
        $this->size_500 = $size_500;
    }

    /**
     * @return mixed
     */
    public function getSize750()
    {
        return $this->size_750;
    }

    /**
     * @param mixed $size_750
     */
    public function setSize750($size_750)
    {
        $this->size_750 = $size_750;
    }

    /**
     * @return mixed
     */
    public function getSize1000()
    {
        return $this->size_1000;
    }

    /**
     * @param mixed $size_1000
     */
    public function setSize1000($size_1000)
    {
        $this->size_1000 = $size_1000;
    }
}
