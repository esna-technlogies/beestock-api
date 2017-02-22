<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 26/02/2017
 * Time: 2:26 PM
 */

namespace ImageStock\UserServiceBundle\Serializer;
use JMS\Serializer\SerializerInterface;

/**
 * Class ResponseSerializer
 * @package ImageStock\UserServiceBundle\Serializer
 */
class ResponseSerializer
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ResponseSerializer constructor.
     * @param SerializerInterface $serializer
     * @param string $format
     */
    public function __construct(SerializerInterface $serializer, $format)
    {
        $this->format = $format;
        $this->serializer = $serializer;
    }

    /**
     * Serialize data as an object wrapped with "response"
     * @param $data
     * @return string
     */
    public function success($data)
    {
        return $this->serializer->serialize(['response' => $data], $this->format);
    }
}