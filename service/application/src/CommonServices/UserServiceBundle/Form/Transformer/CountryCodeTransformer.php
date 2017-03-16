<?php

namespace CommonServices\UserServiceBundle\Form\Transformer;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class CountryCodeTransformer
 * @package CommonServices\UserServiceBundle\Form\Transformer
 */
class CountryCodeTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($object)
    {
        /** @var User $object */
        return strtoupper($object->getCountry());
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($countryCode)
    {
        return strtoupper($countryCode);
    }

}