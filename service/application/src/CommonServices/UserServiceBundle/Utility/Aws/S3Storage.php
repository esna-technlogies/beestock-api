<?php

namespace CommonServices\UserServiceBundle\Utility\Aws;
use Aws\S3\PostObjectV4;
use Aws\S3\S3Client;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
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
     * @param string $userDirectoryName
     */
    public function createUserBucket(string $userDirectoryName)
    {
        /*        $request =[
                    'Bucket' => $bucketName,
                    'ACL'    => 'public-read',
                    'LocationConstraint' => $this->serviceContainer->getParameter('aws_region'),
                ];

                $result = $this->s3Client->createBucket($request);*/
        /*
                $bucketCors =[
                    'Bucket' => $bucketName,
                    'CORSConfiguration' => [
                        'CORSRules' => [
                            [
                                'AllowedHeaders' => ['*'],
                                'AllowedMethods' => ['POST', 'GET', 'PUT', 'DELETE'], // REQUIRED
                                'AllowedOrigins' => ['*'], // REQUIRED
                                'ExposeHeaders'  => ['Location', 'x-amz-request-id', 'Content-Type', 'Content-Length', 'Date'],
                                'MaxAgeSeconds'  => 3000
                            ],
                        ],
                    ],
                ];*/
        /*

                $this->s3Client->putBucketCors($bucketCors);

                $this->s3Client->putBucketPolicy(array(
                    'Bucket' => $bucketName,
                    'Policy' => json_encode(array(
                        'Statement' => array(
                            array(
                                'Sid' => 'readonly-policy',
                                'Action' => array(
                                    's3:GetBucketAcl',
                                    's3:ListBucket',
                                    's3:GetObject',
                                    's3:PutObject'
                                ),
                                'Effect' => 'Allow',
                                'Principal' => "*",
                                'Resource' => array(
                                    "arn:aws:s3:::{$bucketName}",
                                    "arn:aws:s3:::{$bucketName}/*"
                                ),
                            )
                        )
                    ))
                ));*/

        $defaultUserDirectory = $this->serviceContainer->getParameter('default_user_directory');
        $bucketName = $this->serviceContainer->getParameter('aws_s3_users_bucket');
        $standardUserDirectory = $this->serviceContainer->getParameter('aws_s3_standard_user_directory');

        $this->s3Client->copyObject(array(
            'ACL' => 'public-read',
            'Bucket' =>  $bucketName,
            'CopySource' => $bucketName.'/'.$standardUserDirectory.'/',
            'Key' => $userDirectoryName.'/',
        ));

        $this->s3Client->uploadDirectory(
            $defaultUserDirectory,
            $bucketName.'/'.$userDirectoryName,
            '',
            []
        );

    }

    /**
     * @param string $key
     * @param string $bucketName
     *
     * @return object
     */
    public function getS3Object(string $key, string $bucketName)
    {
        $request =[
            'Bucket'    => $bucketName,
            'Key'       => $key,
        ];

        return $this->s3Client->getObject($request);
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
     * @return object
     */
    public function emptyUploadsBucket()
    {
        $request =[
            'Bucket' => $this->serviceContainer->getParameter('aws_s3_users_uploads_bucket'),
        ];

        $this->s3Client->deleteBucket($request);

        // Wait until the bucket is not accessible
        return $this->s3Client->waitUntil('BucketNotExists', $request);
    }

    /**
     * @param array $object
     *
     * @return object
     */
    public function copyS3Object(array $object)
    {
        return $this->s3Client->copyObjectAsync(
            [
                'Bucket'     => $object['to']['bucket'],
                'Key'        => $object['to']['key'],
                'CopySource' => "{$object['from']['bucket']}/{$object['from']['key']}",
            ]
        );
    }

    /**
     * @param string $bucketName
     * @param string $userDirectory
     *
     * @return PostObjectV4 | null
     */
    public function getFileUploadPolicy(string $bucketName, string $userDirectory)  : PostObjectV4
    {
        // Set some defaults for form input fields
        $formInputs = ['acl' => 'public-read'];

        // Construct an array of conditions for policy
        $options = [
            ['acl' => 'public-read'],
            ['bucket' => $bucketName],
            ['starts-with', '$key', $userDirectory.'/'],
        ];

        // Optional: configure expiration time string
        $expires = '+2 hours';

        $postObject = new PostObjectV4(
            $this->s3Client,
            $bucketName,
            $formInputs,
            $options,
            $expires
        );

        $postObject->setFormInput("key", $userDirectory.'/'.time().'_'.$postObject->getFormInputs()["key"]);
        $postObject->setFormInput("bucket", $bucketName);

        return $postObject;
    }
}