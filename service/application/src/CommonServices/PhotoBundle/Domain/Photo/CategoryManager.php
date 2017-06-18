<?php

namespace CommonServices\PhotoBundle\Domain\Photo;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\PhotoBundle\Repository\CategoryRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CategoryManager
 * @package CommonServices\PhotoBundle\Domain\Photo
 */
class CategoryManager
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryManager constructor.
     * @param Category $category
     * @param CategoryRepository $categoryRepository
     * @param ContainerInterface $container
     */
    public function __construct(Category $category, CategoryRepository $categoryRepository, ContainerInterface $container)
    {
        $this->container = $container;
        $this->category = $category;
        $this->categoryRepository = $categoryRepository;
    }
}