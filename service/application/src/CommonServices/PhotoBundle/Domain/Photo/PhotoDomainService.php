<?php

namespace CommonServices\PhotoBundle\Domain\Photo;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Repository\PhotoRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;

/**
 * Class UserDomainService
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class PhotoDomainService
{
    public static $pendingAccountsChanges = [];

    /**
     * @var PhotoRepository
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ItemService constructor.
     * @param ContainerInterface $container
     * @param PhotoRepository $photoRepository
     */
    public function __construct(ContainerInterface $container, PhotoRepository $photoRepository)
    {
        $this->container = $container;
        $this->userRepository = $photoRepository;
    }

    /**
     * @param array $photoInfo
     * @throws InvalidFormException
     *
     * @return Photo
     */
    public function createPhoto(array $photoInfo) : Photo
    {
        $photoFactory = $this->container->get('photo_service.factory.photo_factory');

        return $photoFactory->createPhotoFromUploadInfo($photoInfo);

    }

    /**
     * @param string $fileUrl
     * @throws InvalidFormException
     *
     * @return string
     */
    public function extractFileId(string $fileUrl) : string
    {
        $fileService = $this->container->get('photo_service.factory.file_storage_factory');

        return $fileService->getFileId($fileUrl);
    }


    /**
     * @param array $categoryInfo
     * @throws InvalidFormException
     *
     * @return Category
     */
    public function createCategory(array $categoryInfo) : Category
    {
        $categoryFactory = $this->container->get('photo_service.factory.category_factory');

        return $categoryFactory->createCategoryFromBasicInfo($categoryInfo);

    }

    /**
     * @param array $fileInfo
     * @return array
     */
    public function generateKeywords(array $fileInfo) : array
    {
        $fileService = $this->container->get('photo_service.factory.file_storage_factory');

        return $fileService->getFileKeywords($fileInfo);
    }
}