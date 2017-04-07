<?php

namespace CommonServices\UserServiceBundle\Controller\SecurityController;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 * @package CommonServices\UserServiceBundle\Controller\SecurityController
 */
class LoginController extends Controller
{
    /**
     * Login user with email or mobile number
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Security",
     *  description="Login user with email or mobile number",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="userName",
     *          "dataType"="string",
     *          "requirement"="Valid email address or or valid mobile number",
     *          "description"="User email or User mobile number"
     *      },
     *      {
     *          "name"="password",
     *          "dataType"="string",
     *          "requirement"="[.]{0,16}",
     *          "description"="user password, minimum of 6 digits, max 16 digits"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user is logged-in and token is provided in the response",
     *         400="Bad request: The password or username is invalid",
     *         401={"Unauthorized, the credentials provided are not valid"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function loginUserAction()
    {
        // this action call is intercepted by the JWT token handler
        // user should never actually get here ..

        return new Response( '', Response::HTTP_NOT_IMPLEMENTED );
    }
}
