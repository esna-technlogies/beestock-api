<?php

namespace CommonServices\PhotoBundle\Domain\File;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\PhotoBundle\Repository\FileStorageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FileStorageDomain
 * @package CommonServices\UserServiceBundle\Domain\File
 */
class FileStorageDomain
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var FileStorageRepository
     */
    private $fileStorageRepository;

    /**
     * ItemService constructor.
     * @param ContainerInterface $container
     * @param FileStorageRepository $fileStorageRepository
     */
    public function __construct(
        ContainerInterface  $container,
        FileStorageRepository   $fileStorageRepository
    )
    {
        $this->container = $container;
        $this->fileStorageRepository = $fileStorageRepository;
        $this->photoDomainService = new FileStorageDomainService($container, $fileStorageRepository);
    }

    /**
     * @return FileStorageDomainService
     */
    public function getDomainService() : FileStorageDomainService
    {
        return $this->photoDomainService;
    }

    /**
     * @param FileStorage $fileStorage
     * @return FileStorageManager
     */
    public function getFileStorageManager(FileStorage $fileStorage) : FileStorageManager
    {
        return new FileStorageManager($fileStorage, $this->fileStorageRepository, $this->container);
    }

    /**
     * Check if user exist
     * @return FileStorageRepository
     */
    public function getFileStorageRepository() : FileStorageRepository
    {
        return $this->fileStorageRepository;
    }
}