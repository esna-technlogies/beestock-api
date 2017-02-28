<?php

namespace CommonServices\UserServiceBundle\Form\Type;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CommonServices\UserServiceBundle\Document\User;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class);
        $builder->add('lastName', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('country', TextType::class);
        $builder->add('termsAccepted', TextType::class);
        $builder->add('password', TextType::class,[
            'property_path' => 'accessInfo'
        ]);
        $builder->add('phoneNumber', TextType::class,[
            'property_path' => 'phoneNumber'
        ]);

        $builder->get('phoneNumber')
            ->addModelTransformer(new CallbackTransformer(
                function ($phoneNumber) {
                    /** @var PhoneNumber $phoneNumber */
                    if(null === $phoneNumber){
                        return null;
                    }
                    return $phoneNumber->getPhoneNumber();
                },
                function ($phoneNumber) {
                    // transform the array to a string
                    return (new PhoneNumber())
                        ->setPhoneNumber($phoneNumber['number'])
                        ->setCountryCode($phoneNumber['countryCode']);
                }
            ));

        $builder->get('password')
            ->addModelTransformer(new CallbackTransformer(
                function ($accessInfo) {
                    /** @var AccessInfo $accessInfo */
                    if(null === $accessInfo){
                        return null;
                    }
                    return $accessInfo->getPassword();
                },
                function ($passwordString) {
                    // transform the array to a string
                    return (new AccessInfo())->setPassword($passwordString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'csrf_protection' => false,
        ));
    }
}
