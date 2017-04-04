<?php

namespace CommonServices\UserServiceBundle\Controller\SecurityController;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class ForgotPasswordController
 * @package CommonServices\UserServiceBundle\Controller\SecurityController
 */
class ForgotPasswordController extends Controller
{
    /**
     * Request a password reset (forgot password)
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Security",
     *  description="Retrieve forgot password",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="userName",
     *          "dataType"="string",
     *          "requirement"="Valid email address or or valid mobile number",
     *          "description"="User email or User mobile number"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user password requested (verification code sent)",
     *         400="Bad request: The username provided is not valid",
     *         404={"No user with the provided username was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function forgotPasswordAction(Request $request)
    {
        $userName = $request->request->get('userName', '');

        $userService = $this->get('user_service.security');

        $userService->retrievePassword($userName);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['success' => 'Please check your mobile or email for verification code.']),
            Response::HTTP_OK
        );
    }
}
