<?php

namespace CommonServices\PhotoBundle\Form\Type;

use CommonServices\PhotoBundle\Document\FileStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class FileStorageType
 * @package CommonServices\PhotoBundle\Form\Type
 */
class FileStorageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fileId', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 120])
                    ]
            ]
        );

        $builder->add('extensions', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 120])
                    ]
            ]
        );

        $builder->add('sizes', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 120])
                    ]
            ]
        );

        $builder->add('user', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 120])
                    ]
            ]
        );

        $builder->add('originalFile', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 255])
                    ]
            ]
        );

        $builder->add('bucket', TextType::class,
            [
                'constraints' =>
                    [
                        new NotBlank(),
                        new Length(['min' => 3, 'max'=> 255])
                    ]
            ]
        );


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event)
        {
            $photo = $event->getData();

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
            'data_class' => FileStorage::class,
            'csrf_protection' => false,
            'uuid' => '',
        ));
    }
}
