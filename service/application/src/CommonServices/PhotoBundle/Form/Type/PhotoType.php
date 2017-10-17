<?php

namespace CommonServices\PhotoBundle\Form\Type;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\PhotoBundle\Document\Storage;
use CommonServices\PhotoBundle\Document\Thumbnails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use function var_dump;

/**
 * Class PhotoType
 * @package CommonServices\PhotoBundle\Form\Type
 */
class PhotoType extends AbstractType
{
    private $options;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options;


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

        $builder->add('keywords', TextType::class,
            [
                'constraints' =>
                    [
                        new NotNull(),
                    ]
            ]
        );

        $builder->add('originalFile', UrlType::class,
            [
                'constraints' =>
                    [
                        new Length(['min' => 3, 'max'=> 255]),
                        new NotNull(),
                    ]
            ]
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $photo = $event->getData();

            if (isset($photo['keywords'])) {

                $keywords = explode(",", $photo['keywords']);

                foreach ($keywords as $key => $value) {
                    if (trim($value) === '') {
                        unset($keywords[$key]);
                    }
                }


                $photo['keywords'] = implode(",", $keywords);
            }

            $event->setData($photo);
        });


        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)
        {
            $photo = $event->getData();

            $sizes = [250, 500, 750 , 1000, 'original'];

            $urls = [];

            /** @var Photo $photo */
            $bucket = $photo->getApproved()? 'beesstock-photos' : 'beesstock-unapproved-files';

            foreach ($sizes as $index => $value){

                $urls[$value] = 'https://'
                    .$bucket.'.s3-us-west-2.amazonaws.com/'
                    .$this->options['fileStorage']['userId'].'/'
                    .$this->options['fileStorage']['fileId'].'/'
                    .$value.'.'.$this->options['fileStorage']['fileExtension'];
            }

            $this->options['fileStorage']['thumbnails'] = $urls;

            $thumbs = new Thumbnails();

            $thumbs->setSize250($urls[250]);
            $thumbs->setSize500($urls[500]);
            $thumbs->setSize750($urls[750]);
            $thumbs->setSize1000($urls[1000]);

            $storage = new Storage();

            $storage->setBucketName($bucket);

            $storage->setUserId($this->options['fileStorage']['userId']);

            $storage->setFileExtensions([$this->options['fileStorage']['fileExtension']]);

            $storage->setFileId($this->options['fileStorage']['fileId']);

            $storage->setOriginalFile($urls['original']);

            $storage->setSizes($thumbs);

            $photo->setFileStorage($storage);

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
            'fileStorage' => [],
        ));
    }
}
