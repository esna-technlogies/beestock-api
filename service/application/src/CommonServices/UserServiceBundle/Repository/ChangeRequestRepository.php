<?php

namespace CommonServices\UserServiceBundle\Repository;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Class ChangeRequestRepository
 * @package CommonServices\UserServiceBundle\Repository
 */
class ChangeRequestRepository extends DocumentRepository
{
    /**
     * @var [] ChangeRequest
     */
    public static $pendingRequests;

    /**
     * @param string $action
     * @param int $startPage
     * @param int $resultsPerPage
     * @return mixed
     */
    public function findNewChangeRequests(string $action = "", int $startPage, int $resultsPerPage) : QueryPaginationHandler
    {
        $queryPaginationHandler = new QueryPaginationHandler($startPage, $resultsPerPage);

        $queryBuilder = $this->createQueryBuilder();


        if ($action != ""){
            $queryBuilder->field('changeProcessorName')->equals($action);
        }

        $query = $queryBuilder->field('userNotified')->equals(false)
            ->sort('created')
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
     * @param $eventName
     * @param int $limit
     * @return mixed
     */
    public function findAllByEventName($eventName, $limit = 10)
    {
        return $this->createQueryBuilder()
            ->field('eventName')->equals($eventName)
            ->sort('eventName', 'ASC')
            ->limit($limit)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param $eventName
     * @return null|object
     * @throws NotFoundException
     */
    public function findOneByEventName($eventName)
    {
        $changeRequest = parent::findOneBy(['eventName' => $eventName]);

        if(is_null($changeRequest)){
            $errorMessage = sprintf(
                'No change request was found with event name: %s ',
                $eventName
            );

            throw new NotFoundException($errorMessage);
        }
        return $changeRequest;
    }

    /**
     * @param $uuid
     * @return null|object
     * @throws NotFoundException
     */
    public function findByUuid($uuid)
    {
        $changeRequest = parent::findOneBy(['uuid' => $uuid]);

        if(is_null($changeRequest)){
            $errorMessage = sprintf(
                'No change request was found with event name: %s ',
                $uuid
            );

            throw new NotFoundException($errorMessage);
        }
        return $changeRequest;
    }

    /**
     * @param ChangeRequest $changeRequest
     * @return void
     */
    public function save(ChangeRequest $changeRequest)
    {
        $this->dm->persist($changeRequest);
        $this->dm->flush();
    }

    /**
     * @param ChangeRequest $changeRequest
     */
    public function delete(ChangeRequest $changeRequest)
    {
        $this->dm->remove($changeRequest);
        $this->dm->flush();
    }

    /**
     * @return ChangeRequest[]
     */
    public function getPendingRequests()
    {
        return self::$pendingRequests;
    }

    /**
     * @param ChangeRequest $changeRequest
     */
    public function addPendingRequests(ChangeRequest $changeRequest)
    {
        self::$pendingRequests[] = $changeRequest;
    }
}