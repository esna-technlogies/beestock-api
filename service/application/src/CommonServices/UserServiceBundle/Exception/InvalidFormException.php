<?php

namespace CommonServices\UserServiceBundle\Exception;

use Geocoder\Exception\HttpError;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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

        $form = $formErrors->getForm();

        foreach ($form->getErrors() as $error) {
            $errors['global'] = (string) $error->getMessage();
        }
        // Fields
        foreach ($form as $child /** @var Form $child */) {
            foreach ($child as $element) {
                if (!$element->isValid()) {
                    foreach ($element->getErrors() as $error) {
                        $errors[$child->getName()]['message'] = $error->getMessage();
                        $errors[$child->getName()]['messagePluralization'] = $error->getMessagePluralization();
                        //$errors[$child->getName()]['messageParameters'] = $error->getMessageParameters();
                        $errors[$child->getName()]['cause'] = $errors[$child->getName()];
                        $errorCause = $error->getCause();
                        $errors[$child->getName()]['cause']['plural'] = $errorCause->getPlural();
                        $errors[$child->getName()]['cause']['propertyPath'] = $errorCause->getPropertyPath();
                        $errors[$child->getName()]['cause']['invalidValue'] = $errorCause->getInvalidValue();
                        $errors[$child->getName()]['cause']['code'] = $errorCause->getCode();
                        $errors[$child->getName()]['cause']['cause'] = $errorCause->getCause();
                    }
                }
            }
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()]['message'] = $error->getMessage();
                    $errors[$child->getName()]['messagePluralization'] = $error->getMessagePluralization();
                    //$errors[$child->getName()]['messageParameters'] = $error->getMessageParameters();
                    $errors[$child->getName()]['cause'] = $errors[$child->getName()];
                    if ($errorCause = $error->getCause()) {
                        $errors[$child->getName()]['cause']['plural'] = $errorCause->getPlural();
                        $errors[$child->getName()]['cause']['propertyPath'] = $errorCause->getPropertyPath();
                        $errors[$child->getName()]['cause']['invalidValue'] = $errorCause->getInvalidValue();
                        $errors[$child->getName()]['cause']['code'] = $errorCause->getCode();
                        $errors[$child->getName()]['cause']['cause'] = $errorCause->getCause();
                    }
                }
            }
        }

        self::$formErrors =  $errors;
        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return Form
     */
    public function getFormErrors()
    {
        return self::$formErrors;
    }
}