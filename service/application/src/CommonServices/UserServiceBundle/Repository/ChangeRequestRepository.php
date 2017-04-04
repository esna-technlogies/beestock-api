<?php

namespace CommonServices\UserServiceBundle\Repository;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\lib\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChangeRequestRepository
 * @package CommonServices\UserServiceBundle\Repository
 */
class ChangeRequestRepository extends DocumentRepository
{
    /**
     * @param QueryPaginationHandler $queryPaginationHandler
     * @param string $action
     * @return QueryPaginationHandler
     */
    public function findAllChangeRequests(QueryPaginationHandler $queryPaginationHandler, string $action)
    {
        $query = $this->createQueryBuilder()
            ->field('action')->equals($action)
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
     * @throws InvalidArgumentException
     */
    public function findOneByEventName($eventName)
    {
        $changeRequest = parent::findOneBy(['eventName' => $eventName]);

        if(is_null($changeRequest)){
            $errorMessage = sprintf(
                'No change request was found with event name: %s ',
                $eventName
            );

            throw new InvalidArgumentException($errorMessage, Response::HTTP_NOT_FOUND);
        }
        return $changeRequest;
    }

    /**
     * @param $uuid
     * @return null|object
     * @throws InvalidArgumentException
     */
    public function findByUuid($uuid)
    {
        $changeRequest = parent::findOneBy(['uuid' => $uuid]);

        if(is_null($changeRequest)){
            $errorMessage = sprintf(
                'No change request was found with event name: %s ',
                $uuid
            );

            throw new InvalidArgumentException($errorMessage, Response::HTTP_NOT_FOUND);
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

}