<?php

namespace CommonServices\UserServiceBundle\Repository;

use CommonServices\UserServiceBundle\lib\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Doctrine\ODM\MongoDB\DocumentRepository;
use CommonServices\UserServiceBundle\Document\User;
use MongoDB\BSON\Regex;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserRepository
 * @package UserServiceBundle\Repository
*/
class UserRepository extends DocumentRepository
{
    /**
     * @param QueryPaginationHandler $queryPaginationHandler
     * @return mixed
     */
    public function findAllUsers(QueryPaginationHandler $queryPaginationHandler)
    {
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
     * @param $name
     * @param int $limit
     * @return mixed
     */
    public function findAllByName($name, $limit = 10)
    {
        return $this->createQueryBuilder()
            ->field('fullName')->equals(new Regex($name, 'i'))
            ->sort('fullName', 'ASC')
            ->limit($limit)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * Finds a user by email address
     *
     * @param string $email
     * @return object
     */
    public function findOneByEmail(string $email)
    {
        return $this->getDocumentPersister()->load(['email' =>  $email]);
    }

    /**
     * Finds a user by mobile number
     * @param string $mobileNumber
     * @param string $internationalMobileNumber
     * @return object
     */
    public function findOneByMobileNumber(string $internationalMobileNumber, string $mobileNumber= null)
    {
        $query = $this->createQueryBuilder();
        $query->addOr($query->expr()->field('mobileNumber.internationalNumber')->equals($internationalMobileNumber));

        if($mobileNumber){
            $query->addOr($query->expr()->field('mobileNumber.number')->equals($mobileNumber));
        }

        return $query->limit(1)
                ->getQuery()
                ->getSingleResult();
    }

    /**
     * @param $name
     * @return null|object
     */
    public function findOneByName($name)
    {
        $user = parent::findOneBy(['firstName' => $name]);

        if(is_null($user)){
            $errorMessage = sprintf(
                'No user was found with name: %s ',
                $name
            );

            throw new Exception($errorMessage, Response::HTTP_NOT_FOUND);
        }
        return $user;
    }

    /**
     * @param $uuid
     * @return null|object
     */
    public function findByUUID($uuid)
    {
        $user = parent::findOneBy(['uuid' => $uuid]);

        if(is_null($user)){
            $errorMessage = sprintf(
                'No user was found with uuid: %s ',
                $uuid
            );

            throw new Exception($errorMessage, Response::HTTP_NOT_FOUND);
        }
        return $user;
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user)
    {
        $this->dm->persist($user);
        $this->dm->flush();
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $this->dm->remove($user);
        $this->dm->flush();
    }
}