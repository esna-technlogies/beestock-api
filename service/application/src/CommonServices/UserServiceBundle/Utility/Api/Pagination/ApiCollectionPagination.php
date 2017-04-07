<?php

namespace CommonServices\UserServiceBundle\Utility\Api\Pagination;

use CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class ApiCollectionPagination
 * @package CommonServices\UserServiceBundle\Utility\Api
 */
class ApiCollectionPagination
{
    /**
     * @var ApiResultsPage
     */
    protected $currentPage;

    /**
     * @var ApiResultsPage
     */
    protected $nextPage;

    /**
     * @var ApiResultsPage
     */
    protected $previousPage;

    /**
     * @var ApiResultsPage
     */
    protected $lastPage;

    /**
     * @var ApiResultsPage
     */
    protected $firstPage;

    /**
     * @var ApiResultsPage[]
     */
    protected $pageNavigatorSet;

    /**
     * @var object
     */
    protected $resultCollection;

    /**
     * @var Router
     */
    protected $router;
    /**
     * @var string
     */
    private $collectionListingRoute;

    /**
     * ApiCollectionPagination constructor.
     * @param QueryPaginationHandler $queryHandler
     * @param Router $router
     * @param string $collectionListingRoute
     */
    public function __construct(QueryPaginationHandler $queryHandler, Router $router, string $collectionListingRoute)
    {
        $this->queryHandler = $queryHandler;
        $this->router = $router;
        $this->resultCollection = $queryHandler->getQueryResults();
        $this->currentPage = $queryHandler->getCurrentPageNumber();
        $this->collectionListingRoute = $collectionListingRoute;
    }

    /**
     * @param string $collectionKey
     * @return array
     */
    public function getHateoasFriendlyResults(string $collectionKey)
    {
        return [
            $collectionKey => $this->getResultCollection(),
            '_links' => array_merge(
                $this->getCurrentPageFullRepresentation(),
                $this->getPreviousPage(),
                $this->getNextPage(),
                $this->getFirstPage(),
                $this->getLastPage()
            )
        ];
    }

    /**
     * @return array
     */
    public function getCurrentPageFullRepresentation()
    {
        $currentPage = new ApiResultsPage(
            $this->router,
            $this->collectionListingRoute,
            $this->queryHandler,
            $this->queryHandler->getCurrentPageNumber(),
            'self'
        );

        return $currentPage->getPageFullPresentation();
    }

    /**
     * @return array
     */
    public function getCollectionNavigationSet()
    {
        return [
            'navigation' =>
                array_merge(
                    $this->getFirstPage(),
                    $this->getLastPage()
                )
        ];
    }

    /**
     * @return ApiResultsPage[]
     */
    public function getPageNavigatorSet(): array
    {
        return $this->pageNavigatorSet;
    }

    /**
     * @param ApiResultsPage[] $pageNavigatorSet
     */
    public function setPageNavigatorSet(array $pageNavigatorSet)
    {
        $this->pageNavigatorSet = $pageNavigatorSet;
    }

    /**
     * @return object
     */
    public function getResultCollection()
    {
        return $this->resultCollection;
    }

    /**
     * @param object $resultCollection
     */
    public function setResultCollection($resultCollection)
    {
        $this->resultCollection = $resultCollection;
    }

    /**
     * @return int
     */
    public function getCountOfPages()
    {
        $resultsPerPage = $this->queryHandler->getResultsPerPage();
        $totalResults = $this->queryHandler->getCountOfTotalResults();

        return (int) ceil($totalResults / $resultsPerPage);
    }

    /**
     * @return array
     */
    public function getNextPage(): array
    {
        if($this->getCountOfPages() === $this->currentPage){
            return [];
        }

        return $this->getPageSimplePresentation('next', $this->currentPage+1);
    }

    /**
     * @return array
     */
    public function getPreviousPage(): array
    {
        if($this->currentPage === 1){
            return [];
        }

        return $this->getPageSimplePresentation('previous', $this->currentPage-1);
    }

    /**
     * @return array
     */
    public function getFirstPage(): array
    {
        if($this->queryHandler->getCountOfTotalResults() === $this->queryHandler->getResultsInCurrentPage())
        {
            return [];
        }

        return $this->getPageSimplePresentation('first', 1);
    }

    /**
     * @return array
     */
    public function getLastPage(): array
    {
        if($this->queryHandler->getCountOfTotalResults() === $this->queryHandler->getResultsInCurrentPage())
        {
            return [];
        }
        $lastPage = $this->getCountOfPages();

        return $this->getPageSimplePresentation('last', $lastPage);
    }

    /**
     * @param string $pageName
     * @param int $pageNumber
     * @return array
     */
    public function getPageSimplePresentation(string $pageName, int $pageNumber)
    {
        $page = new ApiResultsPage(
            $this->router,
            $this->collectionListingRoute,
            $this->queryHandler,
            $pageNumber,
            $pageName
        );

        return $page->getPageSimplePresentation();
    }
}