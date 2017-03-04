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
    static public $formErrors =[];

    /**
     * InvalidFormException constructor.
     * @param String $message
     * @param FormErrorIterator $formErrors
     */
    public function __construct(String $message, FormErrorIterator $formErrors)
    {
        $errors =[];

        foreach ($formErrors as $form){
            $errors[$formErrors->getForm()->getName()]=
                [
                    $formErrors->getChildren()
                ];
        }

        self::$formErrors = $errors;

        parent::__construct($message);
    }

    /**
     * @return Form
     */
    public function getFormErrors()
    {
        return self::$formErrors;
    }
}