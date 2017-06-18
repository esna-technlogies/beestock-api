<?php

namespace CommonServices\PhotoBundle\Form\Type;

use CommonServices\PhotoBundle\Document\Category;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class CategoryType
 * @package CommonServices\PhotoBundle\Form\Type
 */
class CategoryType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CategoryType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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

        $builder->add('file', FileType::class,
            [
                'constraints' =>
                    [
                        new NotNull(),
                    ]
            ]
        );


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event)
        {
            /** @var Category $category */
            $category = $event->getData();

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $category->getPhotoFile();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->container->getParameter('temp_uploads_directory'),
                $fileName
            );

            $category->setPhotoFile($fileName);

            $event->setData($category);
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'data_class' => Category::class,
            'csrf_protection' => false,
            'uuid' => '',
        ));
    }
}
