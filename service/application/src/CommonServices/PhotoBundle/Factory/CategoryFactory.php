<?php

namespace CommonServices\PhotoBundle\Factory;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\PhotoBundle\Form\Processor\CategoryInfoProcessor;
use CommonServices\PhotoBundle\Repository\CategoryRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PhotoFactory
 * @package CommonServices\PhotoBundle\Factory
 */
class CategoryFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(ContainerInterface $container, CategoryRepository $categoryRepository)
    {
        $this->container = $container;
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategoryFromBasicInfo(array $categoryInfo) : Category
    {

        $photosDirectory = $this->container->getParameter('temp_uploads_directory');

        $categoryEntity = new Category();

        $categoryInfoProcessor = new CategoryInfoProcessor($this->container->get('form.factory'));

        $category = $categoryInfoProcessor->processForm($categoryEntity, $categoryInfo, true, $photosDirectory);

        $this->categoryRepository->save($category);

        return $category;
    }
}