<?php

namespace CommonServices\UserServiceBundle\Form\Transformer;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class PhoneNumberTransformer
 * @package CommonServices\UserServiceBundle\Form\Transformer
 */
class PhoneNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * UserType constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($mobileNumber)
    {
        /** @var PhoneNumber $mobileNumber */
        if(null === $mobileNumber){
            return null;
        }
        return $mobileNumber->getInternationalNumber();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($mobileNumber)
    {
        return (new PhoneNumber())
            ->setNumber($mobileNumber['number'])
            ->setCountryCode($mobileNumber['countryCode']);
    }
}