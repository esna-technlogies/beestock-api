<?php

namespace CommonServices\Photobundle\Form\Processor;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\PhotoBundle\Form\Type\CategoryType;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;

/**
 * Class CategoryInfoProcessor
 * @package CommonServices\Photobundle\Form\Processor
 */
final class CategoryInfoProcessor extends AbstractProcessor
{
    /**
     * Process Category Form
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param Category $category
     * @param array $parameters
     * @param boolean $clearMissingInfo
     *
     * @return Category
     * @throws InvalidFormException
     * @internal param Photo $photo
     */
    public function processForm(Category $category, array $parameters, $clearMissingInfo = true) : Category
    {
        $uuid = is_null($category->getUuid())? '' : $category->getUuid();

        $form =  $this->formFactory
                        ->createBuilder(CategoryType::class, $category, ['uuid' => $uuid])
                        ->getForm();
        $form->submit($parameters, $clearMissingInfo);

        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form->getErrors(true, true));
        }

        return $form->getData();
    }
}