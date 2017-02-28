<?php

namespace CommonServices\UserServiceBundle\Processor;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Form\Type\UserType;

/**
 * Class UserProcessor
 * @package CommonServices\UserServiceBundle\Processor
 */
final class UserProcessor extends AbstractProcessor
{
    /**
     * Process Mail Form
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param User $user
     * @param array $parameters
     * @return mixed
     * @throws InvalidFormException
     */
    public function processForm(User $user, array $parameters)
    {
        $form =  $this->formFactory
                        ->createBuilder(UserType::class, $user)
                        ->getForm();

        $form->submit($parameters);


        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form->getErrors());
        }

        return $form->getData();
    }

}