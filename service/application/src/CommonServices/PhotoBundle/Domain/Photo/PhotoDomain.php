<?php

namespace CommonServices\PhotoBundle\Domain\Photo;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\PhotoBundle\Document\File;
use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Repository\CategoryRepository;
use CommonServices\PhotoBundle\Repository\FileRepository;
use CommonServices\PhotoBundle\Repository\PhotoRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserDomain
 * @package CommonServices\UserServiceBundle\Domain\User
 */
class PhotoDomain
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Photo
     */
    private $photoRepository;

    /**
     * @var PhotoDomainService
     */
    private $photoDomainService;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * ItemService constructor.
     * @param ContainerInterface $container
     * @param PhotoRepository $photoRepository
     * @param CategoryRepository $categoryRepository
     * @param FileRepository $fileRepository
     */
    public function __construct(
        ContainerInterface  $container,
        PhotoRepository     $photoRepository,
        CategoryRepository  $categoryRepository,
        FileRepository      $fileRepository
    )
    {
        $this->container = $container;
        $this->photoRepository = $photoRepository;
        $this->categoryRepository = $categoryRepository;
        $this->fileRepository = $fileRepository;
        $this->photoDomainService = new PhotoDomainService($container, $photoRepository);
    }

    /**
     * @return PhotoDomainService
     */
    public function getDomainService() : PhotoDomainService
    {
        return $this->photoDomainService;
    }

    /**
     * @param Photo $photo
     * @return PhotoManager
     */
    public function getPhoto(Photo $photo) : PhotoManager
    {
        return new PhotoManager($photo, $this->photoRepository, $this->container);
    }

    /**
     * @param Category $category
     * @return CategoryManager
     */
    public function getCategory(Category $category) : CategoryManager
    {
        return new CategoryManager($category, $this->categoryRepository, $this->container);
    }

    /**
     * @param File $file
     * @return FileManager
     */
    public function getFileManager(File $file) : FileManager
    {
        return new FileManager($file, $this->fileRepository, $this->container);
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
     * Check if user exist
     * @return CategoryRepository
     */
    public function getCategoryRepository() : CategoryRepository
    {
        return $this->categoryRepository;
    }

    /**
     * Check if user exist
     * @return FileRepository
     */
    public function getFileRepository() : FileRepository
    {
        return $this->fileRepository;
    }
}