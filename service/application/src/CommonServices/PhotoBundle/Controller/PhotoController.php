<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\ApiCollectionPagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PhotoController extends Controller
{
    const PHOTO_COLLECTION_LISTING_RESULTS_PER_PAGE = 2;

    /**
     * Lists all photos in the system
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo",
     *  description="lists all photos in the system",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  filters={
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="limit", "dataType"="integer"}
     *  },
     *  statusCodes={
     *         200="Returned when successful, all photos are listed",
     *         400="Bad request: The system is unable to process the request",
     *         404="No photos were found"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function listAction()
    {
        $startPage      = abs(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options'=>['default' => 1 ]]));
        $resultsPerPage = abs(filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT,
            ['options'=>['default' => self::PHOTO_COLLECTION_LISTING_RESULTS_PER_PAGE ]]
        ));

        $resultsHandler =  $this->get('photo_service.photo_domain')->getPhotoRepository()->findAllPhotos($startPage, $resultsPerPage);

        $resultsPaginator = new ApiCollectionPagination(
            $resultsHandler,
            $this->get('router'),
            'photo_service_list_photos'
        );

        if (!$resultsPaginator->getResultCollection()) {
            throw $this->createNotFoundException('No photos found in the system.');
        }

        $results = $resultsPaginator->getHateoasFriendlyResults('photos');

        return new Response(
            $this->get('user_service.response_serializer')->serialize($results),
            Response::HTTP_OK
        );
    }

    /**
     * This end point can be used to create a new photo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo",
     *  description="Create a new user",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  parameters={
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="^[a-zA-Z]*$",
     *          "description"="Title of the photo"
     *      },
     *      {
     *          "name"="description",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="^[a-zA-Z]*$",
     *          "description"="Description og the photo"
     *      },
     *      {
     *          "name"="user",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="V5 UUID",
     *          "description"="UUID of the user who submitted the photo"
     *      },
     *      {
     *          "name"="category",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="V5 UUID",
     *          "description"="UUID of the category that the photo belongs to"
     *      },
     *      {
     *          "name"="keywords",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="A comma separated imploded keywords (children, kid, laughing)",
     *          "description"="The keywords used to describe the photo"
     *      },
     *      {
     *          "name"="s3File",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="*.*",
     *          "description"="A valid url to the file uploaded to amazon S3"
     *      },
     *      {
     *          "name"="suggestedPrice",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="[.]{0,16}",
     *          "description"="the price the owner of the photo suggests"
     *      }
     *  },
     *  tags={"stable"},
     *  statusCodes={
     *         201="Returned when the photo is successfully created",
     *         400="Bad request: The system is unable to process the request due to the following errors",
     *         500="The system is unable to create the photo due to a server side error"
     *  }
     * )
     */
    public function newPhotoAction(Request $request)
    {
        $photoInfo = $request->request->all();

        $photoServiceDomain = $this->get('photo_service.photo_domain');

        $photo = $photoServiceDomain->getDomainService()->createPhoto($photoInfo);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_CREATED
        );
    }


    /**
     * Get a Photo by Unique Identifier (UUID)
     * @param Photo $photo
     *
     * @ParamConverter()
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo",
     *  description="Get a category by UUID",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Unique identifier of the photo"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, photo details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No photo with the provided UUID was found"},
     *         500="The system is unable to get the photo details due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function getAction(Photo $photo = null)
    {
        if (is_null($photo)) {
            throw new NotFoundException("Photo not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_OK
        );
    }


    /**
     * This end point can be used to get a list of suggested keywords of a given photo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo",
     *  description="Get Photo suggested keywords",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  parameters={
     *      {
     *          "name"="s3file",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="^[a-zA-Z]*$",
     *          "description"="Url of the S3 file (only JPG or PNG photos)"
     *      },
     *  },
     *  tags={"stable"},
     *  statusCodes={
     *         201="Returned when the photo keywords are successfully generated",
     *         400="Bad request: The system is unable to process the request due to the following errors",
     *         500="The system is unable to extract or suggest keywords based on the given photo due to a server side error"
     *  }
     * )
     */
    public function keywordsAction(Request $request)
    {

        $fileInfo = $request->request->all();

        $photoServiceDomain = $this->get('photo_service.photo_domain');

        $fileKeywords = $photoServiceDomain->getDomainService()->generateKeywords($fileInfo);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['keywords' => $fileKeywords]),
            Response::HTTP_OK
        );
    }

}
