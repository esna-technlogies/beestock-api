<?php

namespace CommonServices\UserServiceBundle\Utility\Aws;
use Aws\S3\PostObjectV4;
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
     * SmsSender constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;

    }

    /**
     * @param string $bucketName
     *
     * @return object
     */
    public function createBucket(string $bucketName)
    {
        /** @var  \Aws\S3\S3Client  $s3Client */
        $s3Client = $this->serviceContainer->get('aws.s3');

        $request =[
            'Bucket' => $bucketName,
            'LocationConstraint' => $this->serviceContainer->getParameter('aws_region'),
        ];

        return $s3Client->createBucket($request);
    }

    /**
     * @param string $bucketName
     *
     * @return object
     */
    public function deleteBucket(string $bucketName)
    {
        /** @var  \Aws\S3\S3Client  $s3Client */
        $s3Client = $this->serviceContainer->get('aws.s3');

        $request =[
            'Bucket' => $bucketName,
        ];

        $s3Client->deleteBucket($request);

        // Wait until the bucket is not accessible
        return $s3Client->waitUntil('BucketNotExists', $request);
    }

    /**
     * @param string $bucketName
     *
     * @return PostObjectV4 | null
     */
    public function getFileUploadPolicy(string $bucketName)  : PostObjectV4
    {
        /** @var  \Aws\S3\S3Client  $s3Client */
        $s3Client = $this->serviceContainer->get('aws.s3');

        $bucket = $bucketName;

        // Set some defaults for form input fields
        $formInputs = ['acl' => 'public-read'];

        // Construct an array of conditions for policy
        $options = [
            ['acl' => 'public-read'],
            ['bucket' => $bucket],
            ['starts-with', '$key', 'user/eric/'],
        ];

        // Optional: configure expiration time string
        $expires = '+2 hours';

        $postObject = new PostObjectV4(
            $s3Client,
            $bucket,
            $formInputs,
            $options,
            $expires
        );

        return $postObject;
    }
}