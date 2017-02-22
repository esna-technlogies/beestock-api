<?php

namespace MailBoxBundle\Processor;

use MailBoxBundle\Document\Mail;
use MailBoxBundle\Exception\InvalidFormException;
use MailBoxBundle\Form\MailType;

/**
 * Class MailProcessor
 * @package MailBoxBundle\Processor
 */
final class MailProcessor extends AbstractProcessor
{
    /**
     * Process Mail Form
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param Mail $mail
     * @param array $parameters
     * @return mixed
     * @throws InvalidFormException
     */
    public function processForm(Mail $mail, array $parameters)
    {
        $form = $this->formFactory->create(MailType::class, $mail);
        $form->submit($parameters);

        if ($form->isValid()) {
            return $form->getData();
        }

        throw new InvalidFormException(static::MSG_INVALID_SUBMITTED_DATA, $form);
    }

}