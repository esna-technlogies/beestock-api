<?php

namespace CommonServices\UserServiceBundle\Utility\Aws;
use Aws\S3\PostObjectV4;
use Aws\S3\S3Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SendSMS
 * @package CommonServices\UserServiceBundle\Utility\Aws
 */
class S3Storage
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @var S3Client
     */
    private $s3Client;


    /**
     * SmsSender constructor.
     * @param ContainerInterface $serviceContainer
     * @param S3Client $s3Client
     */
    public function __construct(ContainerInterface $serviceContainer, S3Client $s3Client)
    {
        $this->serviceContainer = $serviceContainer;

        $this->s3Client = $s3Client;
    }

    /**
     * @param string $bucketName
     *
     * @return object
     */
    public function createBucket(string $bucketName)
    {
        $request =[
            'Bucket' => $bucketName,
            'LocationConstraint' => $this->serviceContainer->getParameter('aws_region'),
            'CORSConfiguration' => [
                'CORSRules' => [
                    [
                        'AllowedHeaders' => ['Authorization'],
                        'AllowedMethods' => ['POST', 'GET', 'PUT', 'DELETE', 'OPTIONS'], // REQUIRED
                        'AllowedOrigins' => ['*'], // REQUIRED
                        'ExposeHeaders'  => ['Location', 'x-amz-request-id'],
                        'MaxAgeSeconds'  => 3000
                    ],
                ],
            ],
        ];

        return $this->s3Client->createBucket($request);
    }

    /**
     * @param string $bucketName
     *
     * @return object
     */
    public function deleteBucket(string $bucketName)
    {
        $request =[
            'Bucket' => $bucketName,
        ];

        $this->s3Client->deleteBucket($request);

        // Wait until the bucket is not accessible
        return $this->s3Client->waitUntil('BucketNotExists', $request);
    }

    /**
     * @param string $bucketName
     * @param string $directory
     *
     * @return PostObjectV4 | null
     */
    public function getFileUploadPolicy(string $bucketName, string $directory)  : PostObjectV4
    {
        $bucket = $bucketName;

        // Set some defaults for form input fields
        $formInputs = ['acl' => 'public-read'];

        // Construct an array of conditions for policy
        $options = [
            ['acl' => 'public-read'],
            ['bucket' => $bucket],
            ['starts-with', '$key', $directory.'/'],
        ];

        // Optional: configure expiration time string
        $expires = '+2 hours';

        $postObject = new PostObjectV4(
            $this->s3Client,
            $bucket,
            $formInputs,
            $options,
            $expires
        );

        $postObject->setFormInput("key", $directory.'/'.$postObject->getFormInputs()["key"]);

        return $postObject;
    }
}