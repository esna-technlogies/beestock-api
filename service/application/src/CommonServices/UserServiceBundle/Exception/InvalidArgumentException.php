<?php

namespace CommonServices\UserServiceBundle\Exception;

use Throwable;

/**
 * Class InvalidArgumentException
 * @package CommonServices\UserServiceBundle\Exception
 */
class InvalidArgumentException extends \Exception
{
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}