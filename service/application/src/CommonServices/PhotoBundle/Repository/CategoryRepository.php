<?php

namespace CommonServices\PhotoBundle\Repository;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Class CategoryRepository
 * @package PhotoBundle\Repository
*/
class CategoryRepository extends DocumentRepository
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
     * @return null|Category
     */
    public function findByUuid($uuid) : ?Category
    {
        /** @var Category  $category */
        $category = parent::findOneBy(['uuid' => $uuid]);

        if(is_null($category)){
            $errorMessage = sprintf(
                'No category was found with uuid: %s ',
                $uuid
            );

            throw new NotFoundException($errorMessage);
        }
        return $category;
    }

    /**
     * @param Category $category
     * @return void
     */
    public function save(Category $category)
    {
        $this->dm->persist($category);
        $this->dm->flush();
    }

    /**
     * @param Category $category
     */
    public function delete(Category $category)
    {
        $this->dm->remove($category);
        $this->dm->flush();
    }

    /**
     * @param Category $category
     */
    public function softDelete(Category $category)
    {
        $this->dm->remove($category);
        $this->dm->flush();
    }
}