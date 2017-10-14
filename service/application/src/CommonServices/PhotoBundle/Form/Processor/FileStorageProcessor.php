<?php

namespace CommonServices\PhotoBundle\Form\Processor;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\PhotoBundle\Form\Type\FileStorageType;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class UserProcessor
 * @package CommonServices\Photobundle\Processor
 */
class FileStorageProcessor
{
    const MSG_INVALID_SUBMITTED_DATA = 'Invalid submitted data';
    const MSG_INVALID_ARGUMENT_METHOD = 'Invalid argument $method.';

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * PhotoInfoProcessor constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Process File info
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param FileStorage $file
     * @param array $parameters
     * @param boolean $clearMissingInfo
     *
     * @return FileStorage
     * @throws InvalidFormException
     */
    public function processForm(FileStorage $file, array $parameters, $clearMissingInfo = true) : FileStorage
    {
        $uuid = is_null($file->getUuid())? '' : $file->getUuid();

        $form =  $this->formFactory
            ->createBuilder(FileStorageType::class, $file, ['uuid' => $uuid])
            ->getForm();
        $form->submit($parameters, $clearMissingInfo);

        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form->getErrors(true, true));
        }

        return $form->getData();
    }
}