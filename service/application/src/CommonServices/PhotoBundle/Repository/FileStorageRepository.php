<?php

namespace CommonServices\PhotoBundle\Repository;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Class FileStorageRepository
 * @package PhotoBundle\Repository
*/
class FileStorageRepository extends DocumentRepository
{
    /**
     * @param int $startPage
     * @param int $resultsPerPage
     * @return mixed
     */
    public function findAllFiles(int $startPage, int $resultsPerPage) : QueryPaginationHandler
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
     * @param $uuid
     * @throws NotFoundException
     * @return FileStorage
     */
    public function findByUuid($uuid) : ?FileStorage
    {
        /** @var FileStorage $file */
        $file = parent::findOneBy(['uuid' => $uuid]);

        if(is_null($file)){
            $errorMessage = sprintf(
                'No File was found with uuid: %s ',
                $uuid
            );

            throw new NotFoundException($errorMessage);
        }
        return $file;
    }

    /**
     * @param FileStorage $file
     * @return void
     */
    public function save(FileStorage $file)
    {
        $this->dm->persist($file);
        $this->dm->flush();
    }

    /**
     * @param FileStorage $file
     */
    public function delete(FileStorage $file)
    {
        $this->dm->remove($file);
        $this->dm->flush();
    }

    /**
     * @return void
     */
    public function deleteAll()
    {
        $collection = $this->dm->getDocumentCollection('CommonServices\PhotoBundle\Document\FileStorage');
        $collection->drop();
    }

    /**
     * @param FileStorage $file
     */
    public function softDelete(FileStorage $file)
    {
        $this->dm->remove($file);
        $this->dm->flush();
    }
}