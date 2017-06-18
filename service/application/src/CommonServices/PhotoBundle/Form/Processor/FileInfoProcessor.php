<?php

namespace CommonServices\Photobundle\Form\Processor;

use CommonServices\PhotoBundle\Document\File;
use CommonServices\PhotoBundle\Form\Type\FileType;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;

/**
 * Class FileInfoProcessor
 * @package CommonServices\Photobundle\Processor
 */
final class FileInfoProcessor extends AbstractProcessor
{
    /**
     * Process File information from storage
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param File $file
     * @param array $parameters
     * @param boolean $clearMissingInfo
     *
     * @return File
     * @throws InvalidFormException
     */
    public function processForm(File $file, array $parameters, $clearMissingInfo = true) : File
    {
        $uuid = is_null($file->getUuid())? '' : $file->getUuid();

        $form =  $this->formFactory
                        ->createBuilder(FileType::class, $file, ['uuid' => $uuid])
                        ->getForm();
        $form->submit($parameters, $clearMissingInfo);

        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form->getErrors(true, true));
        }

        return $form->getData();
    }
}