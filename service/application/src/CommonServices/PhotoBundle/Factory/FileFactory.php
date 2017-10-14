<?php

namespace CommonServices\PhotoBundle\Factory;

use CommonServices\PhotoBundle\Document\File;
use CommonServices\PhotoBundle\Form\Processor\FileInfoProcessor;
use CommonServices\PhotoBundle\Repository\FileRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FileFactory
 * @package CommonServices\PhotoBundle\Factory
 */
class FileFactory
{
    /**
     * @var FileRepository
     */
    private $fileRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->container = $container;
    }

    /**
     * @param array $fileInfo
     * @return File
     */
    public function processFileMetadata(array $fileInfo) : File
    {
        // Download file
        $file = $this->downloadFile($fileInfo['originalFile']);

        // check file, make sure it' a valid jog or footage or icon


        // Define file type


        // extract file keywords


        // generate file thumbnails


        // save file in the database


        // delete temp file


        return new File();
    }


    /**
     * @param array $fileInfo
     * @return array
     */
    public function getFileKeywords(array $fileInfo) : array
    {
        // cleansing url
        $count = 1;
        $fileInfo['s3file'] = str_replace('https://', '',$fileInfo['s3file'], $count);
        $info = explode('.s3-us-west-2.amazonaws.com/', $fileInfo['s3file']);

        $info[1] = urldecode($info[1]);


        $rekognitionService = $this->container->get('aws.files.rekognition');
        $lables = $rekognitionService->generatePhotoKeywords($info[1], $info[0]);
        $keywords = [];

        foreach ($lables as $key => $value){
            $keywords[] = $value["Name"];
        }

        return $keywords;
    }




    /**
     * @param string $url
     * @return string
     */
    private function downloadFile(string $url) : string {

        // attempt to generate a filename from the URL's path
        $filename = @preg_replace('~[^\w\-.]+~', '_', iconv('UTF-8', 'ASCII//TRANSLIT', pathinfo(urldecode(parse_url($url, PHP_URL_PATH)), PATHINFO_BASENAME))) ?: md5(time());


        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);

        $directory = $this->container->getParameter('temp_uploads_directory');
        $filename = md5(rand(1,99999)).'_'.$filename;


        $fs = new Filesystem();

        try {
            $fs->mkdir($directory);
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating your directory at ".$e->getPath();
        }


        try {
            $fs->touch($directory.$filename);
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating file ".$e->getPath();
        }

        print $directory.$filename;

        $fp = fopen($directory.$filename,'w+');
        fwrite($fp, $raw);
        fclose($fp);

        return $directory.$filename;
    }
}