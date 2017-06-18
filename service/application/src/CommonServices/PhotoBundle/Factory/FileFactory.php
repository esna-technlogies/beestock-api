<?php

namespace CommonServices\PhotoBundle\Factory;

use CommonServices\PhotoBundle\Document\File;
use CommonServices\Photobundle\Form\Processor\FileInfoProcessor;
use CommonServices\PhotoBundle\Repository\FileRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FileFactory
 * @package CommonServices\PhotoBundle\Factory
 */
class FileFactory
{
    /**
     * @var FileRepository
     */
    private $fileRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->container = $container;
    }

    public function createPhotoFromUploadInfo(array $photoInfo) : File
    {
        $fileEntity = new File();

        $photoInfoProcessor = new FileInfoProcessor($this->container->get('form.factory'));

        $file = $photoInfoProcessor->processForm($fileEntity, $photoInfo, true);

        $this->fileRepository->save($file);

        return $file;
    }
}