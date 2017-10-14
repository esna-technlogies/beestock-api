<?php

namespace CommonServices\PhotoBundle\Domain\File;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\PhotoBundle\Repository\FileStorageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FileStorageManager
 * @package CommonServices\UserServiceBundle\Domain\File
 */
class FileStorageManager
{
    /**
     * @var ContainerInterface
     */

    private $container;
    /**
     * @var FileStorage
     */
    private $file;

    /**
     * @var FileStorageRepository
     */
    private $fileStorageRepository;

    /**
     * UserAccountService constructor.
     * @param FileStorage $file
     * @param FileStorageRepository $fileStorageRepository
     * @param ContainerInterface $container
     */
    public function __construct(FileStorage $file, FileStorageRepository $fileStorageRepository, ContainerInterface $container)
    {
        $this->file = $file;
        $this->fileStorageRepository = $fileStorageRepository;
        $this->container = $container;
    }
}