<?php

namespace CommonServices\Photobundle\Form\Processor;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Form\Type\PhotoType;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;

/**
 * Class UserProcessor
 * @package CommonServices\Photobundle\Processor
 */
final class PhotoInfoProcessor extends AbstractProcessor
{
    /**
     * Process User Form
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param Photo $photo
     * @param array $parameters
     * @param boolean $clearMissingInfo
     *
     * @return Photo
     * @throws InvalidFormException
     */
    public function processForm(Photo $photo, array $parameters, $clearMissingInfo = true) : Photo
    {
        $uuid = is_null($photo->getUuid())? '' : $photo->getUuid();

        $form =  $this->formFactory
                        ->createBuilder(PhotoType::class, $photo, ['uuid' => $uuid])
                        ->getForm();
        $form->submit($parameters, $clearMissingInfo);

        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form->getErrors(true, true));
        }

        return $form->getData();
    }
}