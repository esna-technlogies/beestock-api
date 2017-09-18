<?php

namespace CommonServices\PhotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PhotoController extends Controller
{
    /**
     * Lists all photos in a specific category or with a specific filter criteria
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo",
     *  description="lists all photos that match a specific criteria",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Change Request uuid "
     *      },
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "requirement"="A string of minimum of 6 digits",
     *          "description"="Verification code received by email or phone"
     *      },
     *  },
     *  statusCodes={
     *         204="Returned when successful, request is verified and change has been made",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function listAction()
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
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
     *          "name"="file",
     *          "dataType"="file",
     *          "required"= true,
     *          "requirement"="*.*",
     *          "description"="The file of the photo to be uploaded"
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
}
