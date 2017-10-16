<?php

namespace CommonServices\PhotoBundle\Repository;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Doctrine\ODM\MongoDB\DocumentRepository;
use MongoDB\BSON\Regex;

/**
 * Class PhotoRepository
 * @package PhotoBundle\Repository
*/
class PhotoRepository extends DocumentRepository
{
    /**
     * @param int $startPage
     * @param int $resultsPerPage
     * @return mixed
     */
    public function findAllPhotos(int $startPage, int $resultsPerPage) : QueryPaginationHandler
    {
        $queryPaginationHandler = new QueryPaginationHandler($startPage, $resultsPerPage);

        $query = $this->createQueryBuilder()
            ->sort('created', 'DESC')
            ->limit($queryPaginationHandler->getResultsPerPage())
            ->skip($queryPaginationHandler->getResultsToSkip())
            ->getQuery()
            ->execute()
        ;

        $queryPaginationHandler->setCountOfTotalResults($query->count());
        $queryPaginationHandler->setQueryResults($query->toArray(true));

        return $queryPaginationHandler;
    }
    /**
     * @param string $categoryUuid
     * @return mixed
     */
    public function findRandomPhoto(string $categoryUuid) : ?Photo
    {
        $qb = $this->getDocumentManager()
                   ->createQueryBuilder()
                   ->field('category')->equals($categoryUuid)
        ;
        $count =  $qb->getQuery()->count();
        $skip_count = random_int(1, $count-1);
        $qb->skip($skip_count);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param $name
     * @param int $limit
     * @return mixed
     */
    public function findAllByTitle($name, $limit = 10)
    {
        return $this->createQueryBuilder()
            ->field('title')->equals(new Regex($name, 'i'))
            ->sort('title', 'ASC')
            ->limit($limit)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * Finds a user by mobile number
     * @param string $number
     * @return object
     */
    public function findOneByMobileNumber(string $number)
    {
        $query = $this->createQueryBuilder();
        $query->addOr($query->expr()->field('mobileNumber.number')->equals($number));
        $query->addOr($query->expr()->field('mobileNumber.nationalNumber')->equals($number));
        $query->addOr($query->expr()->field('mobileNumber.internationalNumber')->equals($number));
        $query->addOr($query->expr()->field('mobileNumber.internationalNumberForCalling')->equals($number));

        return $query->limit(1)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Finds a user by mobile number
     * @param string $number
     * @return object
     */
    public function findOneByInternationalMobileNumber(string $number)
    {
        $query = $this->createQueryBuilder()->field('mobileNumber.internationalNumber')->equals($number);

        return $query->limit(1)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param $name
     * @return null|object
     * @throws NotFoundException
     */
    public function findOneByName($name)
    {
        $user = parent::findOneBy(['firstName' => $name]);

        if(is_null($user)){
            $errorMessage = sprintf(
                'No user was found with name: %s ',
                $name
            );

            throw new NotFoundException($errorMessage);
        }
        return $user;
    }

    /**
     * @param $uuid
     * @throws NotFoundException
     * @return null|Photo
     */
    public function findByUuid($uuid) : ?Photo
    {
        /** @var Photo $photo */
        $photo = parent::findOneBy(['uuid' => $uuid]);

        if(is_null($photo)){
            $errorMessage = sprintf(
                'No photo was found with uuid: %s ',
                $uuid
            );

            throw new NotFoundException($errorMessage);
        }
        return $photo;
    }

    /**
     * @param Photo $photo
     * @return void
     */
    public function save(Photo $photo)
    {
        $this->dm->persist($photo);
        $this->dm->flush();
    }

    /**
     * @param Photo $photo
     */
    public function delete(Photo $photo)
    {
        $this->dm->remove($photo);
        $this->dm->flush();
    }

    /**
     * @return void
     */
    public function deleteAll()
    {
        $collection = $this->dm->getDocumentCollection('CommonServices\PhotoBundle\Document\Photo');
        $collection->drop();
    }

    /**
     * @param Photo $photo
     */
    public function softDelete(Photo $photo)
    {
        $this->dm->remove($photo);
        $this->dm->flush();
    }
}