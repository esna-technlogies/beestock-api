<?php

namespace CommonServices\PhotoBundle\Form\Processor;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\PhotoBundle\Form\Type\CategoryType;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class CategoryInfoProcessor
 * @package CommonServices\Photobundle\Form\Processor
 */
class CategoryInfoProcessor
{

    const MSG_INVALID_SUBMITTED_DATA = 'Invalid submitted data';
    const MSG_INVALID_ARGUMENT_METHOD = 'Invalid argument $method.';

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * AbstractProcessor constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Process Category Form
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param Category $category
     * @param array $parameters
     * @param boolean $clearMissingInfo
     *
     * @param string $photosDirectory
     * @return Category
     * @throws InvalidFormException
     * @internal param Photo $photo
     */
    public function processForm(Category $category, array $parameters, $clearMissingInfo = true, string $photosDirectory) : Category
    {
        $uuid = is_null($category->getUuid())? '' : $category->getUuid();

        $form =  $this->formFactory
                        ->createBuilder(CategoryType::class, $category, ['uuid' => $uuid, 'photos_directory' => $photosDirectory])
                        ->getForm();
        $form->submit($parameters, $clearMissingInfo);

        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form->getErrors(true, true));
        }

        return $form->getData();
    }
}