<?php

namespace CommonServices\UserServiceBundle\Form\Processor;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Form\Type\UserType;

/**
 * Class UserProcessor
 * @package CommonServices\UserServiceBundle\Processor
 */
final class UserBasicInfoProcessor extends AbstractProcessor
{
    /**
     * Process User Form
     * it validate the parameters and fill the Item entity with the form parameters
     *
     * @param User $user
     * @param array $parameters
     * @param boolean $clearMissingInfo
     *
     * @return User
     * @throws InvalidFormException
     */
    public function processForm(User $user, array $parameters, $clearMissingInfo = true) : User
    {
        $uuid = is_null($user->getUuid())? '' : $user->getUuid();

        $form =  $this->formFactory
                        ->createBuilder(UserType::class, $user, ['uuid' => $uuid])
                        ->getForm();
        $form->submit($parameters, $clearMissingInfo);

        if(false === $form->isValid()){
            throw new InvalidFormException('Something went wrong !', $form->getErrors(true, true));
        }

        return $form->getData();
    }
}