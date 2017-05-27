<?php

namespace CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension;

/**
 * Class QueryPaginationHandler
 * @package CommonServices\UserServiceBundle\Utility\Api\Pagination\DoctrineExtension
 */
class QueryPaginationHandler
{
    const PageNavigatorPitch = 2;

    /**
     * @var integer
     */
    protected $resultsPerPage = 10;

    /**
     * @var integer
     */
    protected $resultsInCurrentPage;

    /**
     * @var integer
     */
    protected $countOfTotalResults;

    /**
     * @var integer
     */
    protected $resultsToSkip;

    /**
     * @var array
     */
    protected $queryResults = [];

    /**
     * @var integer
     */
    protected $currentPageNumber;

    /**
     * MongoQueryPaginationHandler constructor.
     * @param int $currentPage
     * @param int $resultsPerPage
     */
    public function __construct(int $currentPage, int $resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->currentPageNumber = $currentPage;

        $this->setResultsToSkip();
    }

    /**
     * @return int
     */
    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    /**
     * @param int $currentPageNumber
     */
    public function setCurrentPageNumber(int $currentPageNumber)
    {
        $this->currentPageNumber = $currentPageNumber;
    }

    /**
     * @return int
     */
    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    /**
     * @param int $resultsPerPage
     */
    public function setResultsPerPage(int $resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * @return int
     */
    public function getResultsInCurrentPage(): int
    {
        return count($this->getQueryResults());
    }

    /**
     * @return int
     */
    public function getCountOfTotalResults(): int
    {
        return $this->countOfTotalResults;
    }

    /**
     * @param int $countOfTotalResults
     */
    public function setCountOfTotalResults(int $countOfTotalResults)
    {
        $this->countOfTotalResults = $countOfTotalResults;
    }

    /**
     * @return int
     */
    public function getResultsToSkip(): int
    {
        return $this->resultsToSkip;
    }

    public function setResultsToSkip()
    {
        if($this->currentPageNumber <= 1)
        {
            $this->resultsToSkip = 0;
            return;
        }
        $this->resultsToSkip = ($this->currentPageNumber - 1)* $this->resultsPerPage;
    }

    /**
     * @return array
     */
    public function getQueryResults(): array
    {
        return $this->queryResults;
    }

    /**
     * @param array $queryResults
     */
    public function setQueryResults(array $queryResults)
    {
        $this->queryResults = $queryResults;
    }
}