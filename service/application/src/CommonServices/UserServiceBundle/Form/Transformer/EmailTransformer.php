<?php

namespace CommonServices\UserServiceBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class EmailTransformer
 * @package CommonServices\UserServiceBundle\Form\Transformer
 */
class EmailTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($user)
    {
        $user->setEmail(strtolower($user->getEmail()));

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($user)
    {
    }
}