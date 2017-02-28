<?php

namespace CommonServices\UserServiceBundle\Exception;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormErrorIterator;


/**
 * Class InvalidFormException
 * @package CommonServices\UserServiceBundle\Exception
 */
class InvalidFormException extends \Exception
{

    /**
     * @var Form
     */
    private $formErrors;

    /**
     * InvalidFormException constructor.
     * @param String $message
     * @param FormErrorIterator $formErrors
     */
    public function __construct(String $message, FormErrorIterator $formErrors)
    {
        $this->formErrors = $formErrors;
        parent::__construct($message);
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->formErrors;
    }


}