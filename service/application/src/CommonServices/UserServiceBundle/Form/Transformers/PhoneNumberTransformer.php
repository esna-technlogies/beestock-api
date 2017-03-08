<?php

namespace CommonServices\UserServiceBundle\Form\Transformers;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

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
    public function transform($phoneNumber)
    {
        /** @var PhoneNumber $phoneNumber */
        if(null === $phoneNumber){
            return null;
        }
        return $phoneNumber->getPhoneNumber();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($phoneNumber)
    {
        return (new PhoneNumber())
            ->setPhoneNumber($phoneNumber['number'])
            ->setCountryCode($phoneNumber['countryCode']);
    }
}