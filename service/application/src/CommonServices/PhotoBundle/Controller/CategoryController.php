<?php

namespace CommonServices\PhotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class CategoryController extends Controller
{
    /**
     * Lists all photos in a specific category or with a specific filter criteria
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Category",
     *  description="lists all photos that match a specific criteria",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "required"= true,
     *          "description"="Change Request uuid "
     *      },
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "required"= true,
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



}
