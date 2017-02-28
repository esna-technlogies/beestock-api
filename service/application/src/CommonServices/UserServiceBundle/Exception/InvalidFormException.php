<?php

namespace CommonServices\UserServiceBundle\Exception;

use Symfony\Component\Form\Form;


/**
 * Class InvalidFormException
 * @package MailBoxBundle\Exception
 */
class InvalidFormException extends \Exception
{

    /**
     * @var Form
     */
    private $form;

    /**
     * InvalidFormException constructor.
     * @param $message
     * @param $form
     */
    public function __construct($message, $form)
    {
        $this->form = $form;
        parent::__construct($message);
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }


}