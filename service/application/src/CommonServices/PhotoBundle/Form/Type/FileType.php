<?php

namespace CommonServices\PhotoBundle\Form\Type;

use CommonServices\PhotoBundle\Document\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class PhotoType
 * @package CommonServices\PhotoBundle\Form\Type
 */
class FileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 60])
                    ]
            ]
        );

        $builder->add('description', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 140])
                    ]
            ]
        );

        $builder->add('user', TextType::class,
            [
                'constraints' =>
                    [
                        new NotNull(),
                        new Length(['min' => 3, 'max'=> 100])
                    ]
            ]
        );

        $builder->add('category', TextType::class,
            [
                'constraints' =>
                    [
                        new NotNull(),
                        new Length(['min' => 3, 'max'=> 100])
                    ]
            ]
        );


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event)
        {
            $photo = $event->getData();

//            if(isset($user['email'])) $user['email'] = strtolower($user['email']);
//
//            if(isset($user['country'])) $user['country'] = strtoupper($user['country']);
//
//            if(isset($user['mobileNumber']['countryCode'])) {
//                $user['mobileNumber']['countryCode'] = strtoupper($user['mobileNumber']['countryCode']);
//            }

            $event->setData($photo);
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'data_class' => Photo::class,
            'csrf_protection' => false,
            'uuid' => '',
        ));
    }
}
