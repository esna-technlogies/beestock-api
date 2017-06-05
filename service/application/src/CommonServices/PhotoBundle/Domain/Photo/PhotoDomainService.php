<?php

namespace CommonServices\PhotoBundle\Domain\Photo;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Repository\PhotoRepository;
use Ramsey\Uuid\Uuid;
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
     * @param array $basicAccountInformation
     * @throws InvalidFormException
     *
     * @return Photo
     */
    public function createPhoto(array $basicAccountInformation) : Photo
    {
        $storage = $this->container->get('aws.s3.file_storage');

        $result = $storage->createBucket(Uuid::uuid4()->toString());

        var_dump($result);

        exit;


        //$userFactory = $this->container->get('photo_service.factory.photo_factory');

        return null;//$userFactory->createPhotoFromUploadInfo($basicAccountInformation);
    }
}