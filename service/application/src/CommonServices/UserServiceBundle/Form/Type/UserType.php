<?php

namespace CommonServices\UserServiceBundle\Form\Type;

use CommonServices\UserServiceBundle\Form\Utility\EnglishNumberConverter;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\InternationalMobileNumber;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\NotDisposableEmail;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\UniqueUserEmail;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\UniqueUserMobileNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class UserType
 * @package CommonServices\UserServiceBundle\Form\Type
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 2, 'max'=> 18])
                    ]
            ]
        );

        $builder->add('lastName', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 2, 'max'=> 18])
                    ]
            ]
        );

        $builder->add('email', EmailType::class,
            [
                'constraints' =>
                    [
                        new NotNull(),
                        new UniqueUserEmail([], $options['uuid']),
                        new NotDisposableEmail()
                    ]
            ]
        );

        $builder->add('language', LanguageType::class);
        $builder->add('country', CountryType::class);
        $builder->add('termsAccepted', CheckboxType::class);
        $builder->add('accessInfo', AccessInfoType::class);
        $builder->add('mobileNumber', PhoneNumberType::class,
            [
                'error_bubbling'=> false,
                'constraints' =>
                    [
                        new NotNull(),
                        new UniqueUserMobileNumber([], $options['uuid']),
                        new InternationalMobileNumber(),
                    ]
            ]
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $user = $event->getData();

            if(isset($user['email'])) $user['email'] = strtolower($user['email']);

            if(isset($user['country'])) $user['country'] = strtoupper($user['country']);

            if(isset($user['mobileNumber']['countryCode'])) {
                $user['mobileNumber']['countryCode'] = strtoupper($user['mobileNumber']['countryCode']);
            }
            if(isset($user['mobileNumber']['number'])) {
                $user['mobileNumber']['number'] = (new EnglishNumberConverter($user['mobileNumber']['number']))->convert();
            }
            $event->setData($user);
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'data_class' => User::class,
            'csrf_protection' => false,
            'uuid' => '',
        ));
    }
}
