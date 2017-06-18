<?php

namespace CommonServices\PhotoBundle\Form\Processor;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class AbstractProcessor
 * @package CommonServices\PhotoBundle\Form\Processor
 */
class AbstractProcessor
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

}