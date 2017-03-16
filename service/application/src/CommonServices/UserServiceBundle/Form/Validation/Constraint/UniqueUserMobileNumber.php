<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\UniqueUserMobileNumberValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueUserMobileNumber
 * @package CommonServices\UserServiceBundle\Form\Validation\Constraint
 */
class UniqueUserMobileNumber extends Constraint
{
    /**
     * @var string
     */
    public $message = 'USER_IS_REGISTERED_ERROR - A user with mobile number %string% has been registered before';

    /**
     * @var string
     */
    private $uuid;

    /**
     * UniqueUserEmail constructor.
     * @param null $options
     * @param string $uuid
     */
    public function __construct($options = null, string $uuid)
    {
        $this->uuid = $uuid;
        parent::__construct($options);
    }

    /**
     * @inheritdoc
     */
    public function validatedBy()
    {
        return UniqueUserMobileNumberValidator::class;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}