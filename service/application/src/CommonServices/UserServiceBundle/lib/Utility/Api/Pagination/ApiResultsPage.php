<?php

namespace CommonServices\UserServiceBundle\lib\Utility\Api\Pagination;

use CommonServices\UserServiceBundle\lib\Utility\Api\Pagination\DoctrineExtension\QueryPaginationHandler;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class ApiResultsPage
 * @package CommonServices\UserServiceBundle\lib\Utility\Api\Pagination
 */
class ApiResultsPage
{
    const NAVIGATION_PAGE_NUMBER_KEY = 'page';
    const NAVIGATION_PAGE_LIMIT_KEY  = 'limit';

    /**
     * @var string
     */
    protected $pageUrl;

    /**
     * @var string
     */
    protected $routeUrl;

    /**
     * @var integer
     */
    protected $pageNumber;

    /**
     * @var integer
     */
    protected $resultsPerPage;
    /**
     * @var string
     */
    private $pageRoute;

    /**
     * @var array
     */
    protected $pagePresentation;
    /**
     * @var string
     */
    private $pageName;
    /**
     * @var QueryPaginationHandler
     */
    private $queryPaginationHandler;

    /**
     * ApiResultsPage constructor.
     * @param Router $router
     * @param string $pageRoute
     * @param QueryPaginationHandler $queryPaginationHandler
     * @param string $pageName
     * @param int $pageNumber
     */
    public function __construct(
        Router $router,
        string $pageRoute,
        QueryPaginationHandler $queryPaginationHandler,
        int $pageNumber,
        string $pageName
    )
    {
        $this->router = $router;
        $this->pageRoute = $pageRoute;
        $this->queryPaginationHandler = $queryPaginationHandler;
        $this->pageNumber = $queryPaginationHandler->getCurrentPageNumber();
        $this->resultsPerPage = $queryPaginationHandler->getResultsPerPage();
        $this->pageName = $pageName;
        $this->pageNumber = $pageNumber;
    }

    /**
     * @param array $pagePresentation
     */
    public function setPagePresentation(array $pagePresentation)
    {
        $this->pagePresentation = $pagePresentation;
    }

    /**
     * @return string
     */
    public function getPageUrl(): string
    {
        $pageUrl =  $this->router->generate( $this->pageRoute,
            [
                self::NAVIGATION_PAGE_NUMBER_KEY => $this->pageNumber,
                self::NAVIGATION_PAGE_LIMIT_KEY  => $this->resultsPerPage
            ]
        );

        return $pageUrl;
    }

    /**
     * @param string $pageUrl
     */
    public function setPageUrl(string $pageUrl)
    {
        $this->pageUrl = $pageUrl;
    }

    /**
     * @return array
     */
    public function getPageFullPresentation(): array
    {
        return [
            $this->pageName =>
                [
                    'href' => $this->getPageUrl(),
                    'page_number'   => $this->pageNumber,
                    'results_count' => $this->queryPaginationHandler->getResultsInCurrentPage(),
                ]
        ];
    }

    /**
     * @return array
     */
    public function getPageSimplePresentation(): array
    {
        return [
            $this->pageName =>
                [
                    'href' => $this->getPageUrl(),
                ]
        ];
    }
}