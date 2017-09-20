<?php

namespace CommonServices\Photobundle\Form\Processor;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Form\Type\PhotoType;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class UserProcessor
 * @package CommonServices\Photobundle\Processor
 */
final class PhotoInfoProcessor
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