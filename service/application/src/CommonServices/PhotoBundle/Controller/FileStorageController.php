<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\PhotoBundle\Document\File;
use CommonServices\UserServiceBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class FileStorageController extends Controller
{

    /**
     * This end point creates a new photo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="File Storage",
     *  description="Create a new photo",
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
     *          "name"="originalFile",
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
    public function newFileAction(Request $request)
    {
        $fileInfo = $request->request->all();

        $photoServiceDomain = $this->get('photo_service.photo_domain');

        $photo = $photoServiceDomain->getDomainService()->createPhoto($fileInfo);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_CREATED
        );
    }


    /**
     * Get a file by Unique Identifier (UUID)
     * @param File $file
     *
     * @ParamConverter()
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
    public function getAction(File $file = null)
    {
        if (is_null($file)) {
            throw new NotFoundException("Photo not found", Response::HTTP_NOT_FOUND);
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
