<?php

namespace CommonServices\UserServiceBundle\Repository;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use CommonServices\UserServiceBundle\Utility\Formatter\EmailFormatter;
use CommonServices\UserServiceBundle\Utility\Formatter\MobileNumberFormatter;
use Doctrine\ODM\MongoDB\DocumentRepository;
use CommonServices\UserServiceBundle\Document\User;
use MongoDB\BSON\Regex;

/**
 * Class UserRepository
 * @package UserServiceBundle\Repository
*/
class UserRepository extends DocumentRepository
{
    /**
     * @param int $startPage
     * @param int $resultsPerPage
     * @return mixed
     */
    public function findAllUsers(int $startPage, int $resultsPerPage) : QueryPaginationHandler
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
     * @param string $email
     * @return object
     */
    public function findUserByEmail(string $email)
    {
        return $this->findOneBy(["email"=> $email]);
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
     * @return null|User
     * @throws NotFoundException
     * @return User
     */
    public function findByUuid($uuid) : User
    {
        $user = parent::findOneBy(['uuid' => $uuid]);

        if(is_null($user)){
            $errorMessage = sprintf(
                'No user was found with uuid: %s ',
                $uuid
            );

            throw new NotFoundException($errorMessage);
        }
        return $user;
    }

    /**
     * @param string $userName
     *
     * @throws NotFoundException
     * @return User
     */
    public function findByUserName(string $userName) : User
    {
        $user = null;
        // first try to login with email address
        if(filter_var($userName, FILTER_VALIDATE_EMAIL))
        {
            $email = EmailFormatter::getCleansedEmailAddress($userName);
            $user = $this->findUserByEmail($email);
        }
        // try to login with possible mobile number
        else
        {
            $mobileNumber = MobileNumberFormatter::getCleansedMobileNumberAsPossibleUsername($userName);
            $user = $this->findOneByMobileNumber($mobileNumber);
        }

        if(is_null($user)){
            throw new NotFoundException('User not found');
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
     * @return void
     */
    public function deleteAll()
    {
        $collection = $this->dm->getDocumentCollection('CommonServices\UserServiceBundle\Document\User');
        $collection->drop();
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $this->dm->remove($user);
        $this->dm->flush();
    }

    /**
     * @param User $user
     */
    public function softDelete(User $user)
    {
        $this->dm->remove($user);
        $this->dm->flush();
    }
}