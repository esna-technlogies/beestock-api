<?php

namespace CommonServices\UserServiceBundle\Controller\WebHook;

use CommonServices\UserServiceBundle\Event\User\Account\UserAccountSuccessfullyCreatedEvent;
use function implode;
use function mt_rand;
use function sizeof;
use function strrev;
use function time;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InitializerController
 * @package CommonServices\UserServiceBundle\Controller\WebHook
 */
class InitializerController extends Controller
{
    /**
     * This end point runs pending cron jobs (sends emails, SMSs, runs pending processes)
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Web Hooks",
     *  description="This endpoint resets all users in the system and creates default users",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  statusCodes={
     *         201="Returned when the cron jobs have been run successfully",
     *  }
     * )
     */
    public function createBasicDataAction()
    {

        $this->get('user_service.user_domain')->getUserRepository()->deleteAll();

        $this->get('photo_service.photo_domain')->getPhotoRepository()->deleteAll();

        $this->get('photo_service.photo_domain')->getCategoryRepository()->deleteAll();

        $users = [
            'user1' => [
                'firstName'                 => 'Mohamed',
                'lastName'                  => 'Almasry',
                'email'                     => 'almasry@almasry.ws',
                'language'                  => 'EN',
                'country'                   => 'DE',
                'termsAccepted'             => true,
                'accessInfo'                => ['password'=>'2571986'],
                'mobileNumber'              => [
                    'number'        => '01628596354',
                    'countryCode'   => 'DE',
                ],
            ],
            'user2' => [
                'firstName'                 => 'Mohamed',
                'lastName'                  => 'Elshawadfy',
                'email'                     => 'eng.mg2011@gmail.com',
                'language'                  => 'EN',
                'country'                   => 'DE',
                'termsAccepted'             => true,
                'accessInfo'                => ['password'=>'123456'],
                'mobileNumber'              => [
                    'number'        => '01067855650',
                    'countryCode'   => 'EG',
                ],
            ],
            'user3' => [
                'firstName'                 => 'John',
                'lastName'                  => 'Doe',
                'email'                     => 'john-beestock@almasry.ws',
                'language'                  => 'EN',
                'country'                   => 'DE',
                'termsAccepted'             => true,
                'accessInfo'                => ['password'=>'123456'],
                'mobileNumber'              => [
                    'number'        => '01067855651',
                    'countryCode'   => 'EG',
                ],
            ],
            'user4' => [
                'firstName'                 => 'Alice',
                'lastName'                  => 'In Wonderland',
                'email'                     => 'alice-beestock@almasry.ws',
                'language'                  => 'EN',
                'country'                   => 'DE',
                'termsAccepted'             => true,
                'accessInfo'                => ['password'=>'123456'],
                'mobileNumber'              => [
                    'number'        => '01067855652',
                    'countryCode'   => 'EG',
                ],
            ],
            'user5' => [
                'firstName'                 => 'Jatzek',
                'lastName'                  => 'McLane',
                'email'                     => 'jj.mcLane@gmail.com',
                'language'                  => 'EN',
                'country'                   => 'DE',
                'termsAccepted'             => true,
                'accessInfo'                => ['password'=>'123456'],
                'mobileNumber'              => [
                    'number'        => '01729484859',
                    'countryCode'   => 'DE',
                ],
            ],
            'user6' => [
                'firstName'                 => 'Natalia',
                'lastName'                  => 'Nagkoloudi',
                'email'                     => 'angelcud89@gmail.com',
                'language'                  => 'EN',
                'country'                   => 'DE',
                'termsAccepted'             => true,
                'accessInfo'                => ['password'=>'123456'],
                'mobileNumber'              => [
                    'number'        => '01626446662',
                    'countryCode'   => 'DE',
                ],
            ],
        ];

        $usersIDs =[];
        foreach ($users as $key => $value){
            $usersIDs[] = $this->get('user_service.user_domain')->getDomainService()->createUserAccount($value)->getUuid();
        }

        $categories = [
            [
                'title'        => 'Abstract',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Animals',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Arts',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Backgrounds',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Beauty',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Landmarks',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Business',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Celebrities',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Editorial',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Education',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Food and Drink',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Medical',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Holidays',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Illustrations',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Industrial',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Miscellaneous',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Nature',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Objects',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Parks',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'People',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Religion',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Science',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Signs',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Sports',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Technology',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Transportation',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Vectors',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
            [
                'title'        => 'Vintage',
                'description'  => 'Dummy category generated by the initializer service ..',
            ],
        ];
        $categoryIDs =[];
        foreach ($categories as $key => $value){
            $categoryIDs[] = $this->get('photo_service.photo_domain')->getDomainService()->createCategory($value)->getUuid();
        }

        // Emptying current uploads bucket 

        // preparing users sample photos

        $copiedObjects = [];

        foreach ($categoryIDs as $cIndex => $cid){

            foreach ($usersIDs as $uIndex => $uid){

                $rand = mt_rand(1, 635);

                $copiedObjects[] = [
                    'from' =>[
                        'bucket' => 'beesstock-test-data',
                        'key'    => 'photos/sample-'.$rand.'.jpg',
                    ],
                    'to' =>[
                        'bucket' => $this->getParameter('aws_s3_users_uploads_bucket'),
                        'key'    => $uid.'/'.time().($rand*mt_rand(1, 100)*mt_rand(1, 100)*mt_rand(1, 100)).'_'.$rand.'.jpg',
                    ],
                    'uid'  => $uid,
                    'cid'  => $cid,
                ];
            }
        }


        $photoTitles = [
            'accurate',
            'flood',
            'precede',
            'curtain',
            'truculent',
            'tall',
            'superficial',
            'spoon',
            'kneel',
            'lyrical',
            'corn',
            'theory',
            'proud',
            'Button',
            'Cappuccino',
            'Car',
            'Car-race',
            'Carpet',
            'Carrot',
            'Cave',
            'wall',
            'windy',
            'crack',
            'hope',
            'step',
            'ball',
            'Comet',
            'Compact Disc',
            'Compass',
            'Computer',
            'instrument',
            'smash',
            'cub',
            'roomy',
            'aboard',
            'dry',
            'soda',
            'plate',
            'soda',
            'cats',
            'jobless',
            'complete',
            'Boy',
            'Brain',
            'Bridge',
            'Butterfly',
            'Chair',
            'Chess Board',
            'Chief',
            'Child',
            'Chisel',
            'Chocolates',
            'Church',
            'Circle',
            'Circus',
            'Clock',
            'Clown',
            'Coffee',
            'Coffee-shop',
            'Crystal',
            'Cup',
            'Cycle',
            'Data Base',
            'Desk',
            'Diamond',
            'Dress',
            'Drill',
            'Drink',
            'Drum',
            'Dung',
            'Ears',
            'Earth',
            'Egg',
            'Electricity',
            'Elephant',
            'Eraser',
            'Explosive',
        ];

        $s3Service = $this->get('aws.s3.file_storage');

        foreach ($copiedObjects as $object){

            $s3Service->copyS3Object($object);

            $title = ucwords($photoTitles[rand(0,sizeof($photoTitles)-1)])." ".strtolower(strrev($photoTitles[rand(0,sizeof($photoTitles)-1)]));

            $description = ucwords($photoTitles[rand(0,sizeof($photoTitles)-1)])." ".
                           strtolower(strrev($photoTitles[rand(0,sizeof($photoTitles)-1)]))." ".
                           strtolower(strrev($photoTitles[rand(0,sizeof($photoTitles)-1)]))." ".
                           strtolower(strrev($photoTitles[rand(0,sizeof($photoTitles)-1)]))." ".
                           strtolower(strrev($photoTitles[rand(0,sizeof($photoTitles)-1)]))." ".
                           strtolower(strrev($photoTitles[rand(0,sizeof($photoTitles)-1)]))." ..";

            $keywords = [
                $photoTitles[rand(0,sizeof($photoTitles)-1)],
                $photoTitles[rand(0,sizeof($photoTitles)-1)],
                $photoTitles[rand(0,sizeof($photoTitles)-1)],
                $photoTitles[rand(0,sizeof($photoTitles)-1)],
                $photoTitles[rand(0,sizeof($photoTitles)-1)],
                $photoTitles[rand(0,sizeof($photoTitles)-1)],
            ];

            $photoInfo = [
                'title'          => $title,
                'description'    => $description,
                'user'           => $object['uid'],
                'category'       => $object['cid'],
                'keywords'       => implode(",", $keywords),
                'originalFile'   => 'https://'.$object['to']['bucket'].'.s3-us-west-2.amazonaws.com/'.$object['to']['key'],
                'suggestedPrice' => rand(100, 999),
            ];

            $this->get('photo_service.photo_domain')->getDomainService()->createPhoto($photoInfo);


            sleep(1);
        }



        $this->get('aws.user.sns')->publishUserEvent('test-users', UserAccountSuccessfullyCreatedEvent::NAME);

        return new Response('Perfect !', Response::HTTP_ACCEPTED);
    }
}
