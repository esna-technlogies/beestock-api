<?php

namespace CommonServices\PhotoBundle\Domain\Photo;

use CommonServices\PhotoBundle\Document\File;
use CommonServices\PhotoBundle\Repository\FileRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FileAnalysis
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class FileManager
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var File
     */
    private $file;
    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * UserAccountService constructor.
     * @param File $file
     * @param FileRepository $fileRepository
     * @param ContainerInterface $container
     */
    public function __construct(File $file, FileRepository $fileRepository, ContainerInterface $container)
    {
        $this->file = $file;
        $this->fileRepository = $fileRepository;
        $this->container = $container;
    }
}