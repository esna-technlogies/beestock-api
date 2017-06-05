<?php

namespace CommonServices\PhotoBundle\Domain\Photo;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Repository\PhotoRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserServiceManager
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class PhotoManager
{
    /**
     * @var Photo
     */
    private $photo;

    /**
     * @var PhotoRepository
     */
    private $photoRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserAccountService constructor.
     * @param Photo $photo
     * @param PhotoRepository $photoRepository
     * @param ContainerInterface $container
     */
    public function __construct(Photo $photo, PhotoRepository $photoRepository, ContainerInterface $container)
    {
        $this->photo = $photo;
        $this->photoRepository = $photoRepository;
        $this->container = $container;
    }
}