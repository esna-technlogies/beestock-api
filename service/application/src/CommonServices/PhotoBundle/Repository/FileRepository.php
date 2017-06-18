<?php

namespace CommonServices\PhotoBundle\Repository;

use CommonServices\PhotoBundle\Document\File;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Class FileRepository
 * @package PhotoBundle\Repository
*/
class FileRepository extends DocumentRepository
{
    /**
     * @param int $startPage
     * @param int $resultsPerPage
     * @return mixed
     */
    public function findAllCategories(int $startPage, int $resultsPerPage) : QueryPaginationHandler
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
     * @return File
     */
    public function findByUuid($uuid) : ?File
    {
        /** @var File $file */
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
     * @param File $file
     * @return void
     */
    public function save(File $file)
    {
        $this->dm->persist($file);
        $this->dm->flush();
    }

    /**
     * @param File $file
     */
    public function delete(File $file)
    {
        $this->dm->remove($file);
        $this->dm->flush();
    }

    /**
     * @param File $file
     */
    public function softDelete(File $file)
    {
        $this->dm->remove($file);
        $this->dm->flush();
    }
}