<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\ApiCollectionPagination;
use function isEmpty;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function var_dump;


class FileStorageController extends Controller
{
    const FILES_COLLECTION_LISTING_RESULTS_PER_PAGE = 2;

    /**
     * Lists all storage files in the system
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="File Storage",
     *  description="lists all storage files in the system",
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
            ['options'=>['default' => self::FILES_COLLECTION_LISTING_RESULTS_PER_PAGE ]]
        ));

        $resultsHandler =  $this->get('photo_service.file_storage_domain')->getFileStorageRepository()->findAllFiles($startPage, $resultsPerPage);

        $resultsPaginator = new ApiCollectionPagination(
            $resultsHandler,
            $this->get('router'),
            'photo_service_list_files_uploaded'
        );

        if (!$resultsPaginator->getResultCollection()) {
            throw $this->createNotFoundException('No files found in the system.');
        }

        $results = $resultsPaginator->getHateoasFriendlyResults('files');

        return new Response(
            $this->get('user_service.response_serializer')->serialize($results),
            Response::HTTP_OK
        );
    }


    /**
     * This end point creates a new photo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="File Storage",
     *  description="Create a new storage file",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  parameters={
     *      {
     *          "name"="fileId",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="^[a-zA-Z]*$",
     *          "description"="File ID"
     *      },
     *      {
     *          "name"="extensions",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="comma separated strings",
     *          "description"="imploded string of file extensions"
     *      },
     *      {
     *          "name"="sizes",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="comma separated strings",
     *          "description"="imploded string of file sizes"
     *      },
     *      {
     *          "name"="user",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="V5 UUID",
     *          "description"="UUID of the user who owns the file"
     *      },
     *      {
     *          "name"="originalFile",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="http://url/bucket/file-id.extension",
     *          "description"="A valid url to the file uploaded to amazon S3"
     *      },
     *      {
     *          "name"="bucket",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="^[a-zA-Z]*$",
     *          "description"="the bucket name"
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
    public function newFileAction(Request $request)
    {
        $fileInfo = $request->request->all();

        $photoServiceDomain = $this->get('photo_service.file_storage_domain');

        $photo = $photoServiceDomain->getDomainService()->createFile($fileInfo);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_CREATED
        );
    }


    /**
     * Get a file by Unique Identifier (UUID)
     * @param FileStorage $file
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="File Storage",
     *  description="Get a file by UUID",
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
     *          "name"="fileId",
     *          "dataType"="string",
     *          "requirement"="string fileId",
     *          "description"="identifier of the file"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, file details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No file with the provided fileId was found"},
     *         500="The system is unable to get the photo details due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function getAction(FileStorage $file)
    {

        if ($file == null) {
            throw new NotFoundException("File not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['file' => $file]),
            Response::HTTP_OK
        );
    }

    /**
     * Get a new file upload policy for a given user (UUID)
     * @param User $user
     * @ParamConverter()
     * @return Response
     *
     * @ApiDoc(
     *  section="File Storage",
     *  description="Get a new file upload policy for a give user",
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
     *          "description"="Unique user identifier of the user"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function uploadPolicyAction(User $user = null)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }

        $userDomain   = $this->get('user_service.user_domain');
        $userManager  = $userDomain->getUser($user);
        $uploadPolicy = $userManager->getAccount()->newFileUploadPolicy($this->getParameter('aws_s3_users_uploads_bucket'));

        $formAttributes = $uploadPolicy->getFormAttributes();
        $formInputs     = $uploadPolicy->getFormInputs();

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize([
                    'fileUploadPolicy' =>[
                        'formAttributes' => $formAttributes,
                        'formInputs'     => $formInputs,
                    ]
                ]),
            Response::HTTP_OK
        );
    }
}
