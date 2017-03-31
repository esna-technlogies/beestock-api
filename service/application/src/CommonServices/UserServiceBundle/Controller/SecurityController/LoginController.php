<?php

namespace CommonServices\UserServiceBundle\Controller\SecurityController;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class LoginController
 * @package CommonServices\UserServiceBundle\Controller\SecurityController
 */
class LoginController extends Controller
{
    /**
     * Login user with email or mobile number
     * @param Request $request
     *
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
     *         200="Returned when successful, user is logged in",
     *         400="Bad request: The password provided is invalid",
     *         404={"No user with the provided username was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function loginUserAction(Request $request)
    {
        $userName = $request->request->get('userName', '');
        $password = $request->request->get('password', '');

        $userService = $this->get('user_service.security');

        $userToken = $userService->authenticateUser($userName, $password);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['token' => $userToken]),
            Response::HTTP_OK
        );
    }
}
