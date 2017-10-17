<?php

namespace CommonServices\PhotoBundle\Factory;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Form\Processor\PhotoInfoProcessor;
use CommonServices\PhotoBundle\Repository\PhotoRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PhotoFactory
 * @package CommonServices\PhotoBundle\Factory
 */
class PhotoFactory
{
    /**
     * @var PhotoRepository
     */
    private $photoRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PhotoFactory constructor.
     * @param ContainerInterface $container
     * @param PhotoRepository $photoRepository
     */
    public function __construct(ContainerInterface $container, PhotoRepository $photoRepository)
    {
        $this->photoRepository = $photoRepository;
        $this->container = $container;
    }

    public function createPhotoFromUploadInfo(array $photoInfo, array $fileStorage) : Photo
    {
        $photoEntity = new Photo();

        $photoInfoProcessor = new PhotoInfoProcessor($this->container->get('form.factory'));

        $photo = $photoInfoProcessor->processForm($photoEntity, $photoInfo, $fileStorage, true);

        $this->photoRepository->save($photo);

        return $photo;
    }

}