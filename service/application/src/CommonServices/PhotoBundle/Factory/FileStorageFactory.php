<?php

namespace CommonServices\PhotoBundle\Factory;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\PhotoBundle\Form\Processor\FileStorageProcessor;
use CommonServices\PhotoBundle\Repository\FileStorageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

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