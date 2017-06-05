<?php

namespace CommonServices\PhotoBundle\Domain\Photo;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Repository\PhotoRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserDomain
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class PhotoDomain
{
    /**
     * @var Photo
     */
    private $photoRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var PhotoDomainService
     */
    private $userDomainService;

    /**
     * ItemService constructor.
     * @param ContainerInterface $container
     * @param PhotoRepository $photoRepository
     */
    public function __construct(ContainerInterface $container, PhotoRepository $photoRepository)
    {
        $this->container = $container;
        $this->photoRepository = $photoRepository;
        $this->userDomainService = new PhotoDomainService($container, $photoRepository);
    }

    /**
     * @param Photo $photo
     * @return PhotoManager
     */
    public function getUser(Photo $photo) : PhotoManager
    {
        return new PhotoManager($photo, $this->photoRepository, $this->container);
    }

    /**
     * Check if user exist
     * @return PhotoRepository
     */
    public function getPhotoRepository() : PhotoRepository
    {
        return $this->photoRepository;
    }

    /**
     * @return PhotoDomainService
     */
    public function getDomainService() : PhotoDomainService
    {
        return $this->userDomainService;
    }

    /**
     * processPendingAccountsChanges upon destruction of domain
     */
    public function __destruct()
    {
        //$this->getDomainService()->processPendingAccountsChanges();
    }
}