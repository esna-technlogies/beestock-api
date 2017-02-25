<?php

namespace ImageStock\UserServiceBundle\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ResponseSerializer
 * @package ImageStock\UserServiceBundle\Serializer
 */
class ResponseSerializer
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ResponseSerializer constructor.
     * @param ContainerInterface $serviceContainer
     * @param SerializerInterface $serializer
     */
    public function __construct(ContainerInterface $serviceContainer, SerializerInterface $serializer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->serializer = $serializer;
    }

    /**
     * Serialize data as an object wrapped with "response"
     * @param $data
     * @return string
     */
    public function serialize($data)
    {
        return $this->serializer->serialize(['response' => $data], $this->serviceContainer->getParameter("api_format"));
    }
}