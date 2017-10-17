<?php

namespace CommonServices\PhotoBundle\Factory;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\PhotoBundle\Form\Processor\FileStorageProcessor;
use CommonServices\PhotoBundle\Repository\FileStorageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Class PhotoFactory
 * @package CommonServices\PhotoBundle\Factory
 */
class FileStorageFactory
{
    /**
     * @var FileStorageRepository
     */
    private $fileStorageRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PhotoFactory constructor.
     * @param ContainerInterface $container
     * @param FileStorageRepository $fileStorageRepository
     */
    public function __construct(ContainerInterface $container, FileStorageRepository $fileStorageRepository)
    {
        $this->fileStorageRepository = $fileStorageRepository;
        $this->container = $container;
    }

    /***
     * @param array $fileInfo
     * @return array
     */
    public function getFileKeywords(array $fileInfo) : array
    {
        // cleansing url
        $count = 1;
        $fileInfo['originalFile'] = str_replace('https://', '',$fileInfo['originalFile'], $count);
        $info = explode('.s3-us-west-2.amazonaws.com/', $fileInfo['originalFile']);

        $info[1] = urldecode($info[1]);


        $rekognitionService = $this->container->get('aws.files.rekognition');
        $lables = $rekognitionService->generatePhotoKeywords($info[1], $info[0]);
        $keywords = [];

        foreach ($lables as $key => $value){
            $keywords[] = $value["Name"];
        }

        return $keywords;
    }

    /**
     * @param string $fileUrl
     * @return array
     */
    public function getFileStorageParameters(string $fileUrl) : array
    {
        // cleansing url
        $count = 1;
        $fileUrl = str_replace('https://', '',$fileUrl, $count);
        $info = explode('.s3-us-west-2.amazonaws.com/', $fileUrl);

        $bucketName = urldecode($info[0]);
        $fileKey = urldecode($info[1]);

        $userId = explode('/', $fileKey)[0];
        $fileNameWithExtension = explode('/', $fileKey)[1];

        $fileName = explode('.', $fileNameWithExtension)[0];
        $fileId   = explode('_', $fileName)[0];
        $fileExtension   = explode('.', $fileNameWithExtension)[1];

        return [
            'fileExtension'         => $fileExtension,
            'fileId'                => $fileId,
            'fileNameWithExtension' => $fileNameWithExtension,
            'fileName'              => $fileName,
            'bucketName'            => $bucketName,
            'userId'                => $userId,
        ];
    }

    /***
     * @param array $fileInfo
     * @return FileStorage
     */
    public function createFileFromStorageInfo(array $fileInfo) : FileStorage
    {
        $fileEntity = new FileStorage();

        $fileInfoProcessor = new FileStorageProcessor($this->container->get('form.factory'));

        $file = $fileInfoProcessor->processForm($fileEntity, $fileInfo, true);

        $this->fileStorageRepository->save($file);

        return $file;
    }
}