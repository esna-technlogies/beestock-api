<?php

namespace CommonServices\UserServiceBundle\Utility\Aws;
use Aws\Rekognition\RekognitionClient;
use Aws\Result;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SendSMS
 * @package CommonServices\UserServiceBundle\Utility\Aws
 */
class Rekognition
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @var RekognitionClient
     */
    private $rekognitionClient;


    /**
     * SmsSender constructor.
     * @param ContainerInterface $serviceContainer
     * @param RekognitionClient $rekognitionClient
     */
    public function __construct(ContainerInterface $serviceContainer, RekognitionClient $rekognitionClient)
    {
        $this->serviceContainer = $serviceContainer;

        $this->rekognitionClient = $rekognitionClient;
    }

    /**
     *
     * @param string $key
     * @param string $bucket
     * @return array
     */
    public function generatePhotoKeywords(string $key, string $bucket )  : array
    {

        $result = $this->rekognitionClient->detectLabels([
            'Image' => [
                    'S3Object' => [
                        'Bucket' => $bucket,
                        'Name'   => $key,
                    ],
                ],
                'MaxLabels' => 50,
                'MinConfidence' => 50,
            ]);

        return $result['Labels'];
    }
}