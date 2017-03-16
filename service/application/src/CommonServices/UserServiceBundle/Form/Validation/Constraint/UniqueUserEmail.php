<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\UniqueUserEmailValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueUserEmail
 * @package CommonServices\UserServiceBundle\Form\Validation\Constraint
 */
class UniqueUserEmail extends Constraint
{
    /**
     * @var string
     */
    public $message = 'USER_IS_REGISTERED_ERROR - A user with email %string% has been registered before.';

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
        return UniqueUserEmailValidator::class;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}