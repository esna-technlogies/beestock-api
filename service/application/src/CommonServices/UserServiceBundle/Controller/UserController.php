<?php

namespace CommonServices\UserServiceBundle\Controller;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package CommonServices\UserServiceBundle\Controller
 */
class UserController extends Controller
{
    /**
     * This endpoint lists all the users in the system
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="returns a collections of users in the system",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  statusCodes={
     *         200="Returned when successful, all users are listed",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No users were found"}
     *  }
     * )
     */
    public function listUsersAction()
    {
        $users =  $this->get('user_service.core')->getAllUsers();

        if (!$users) {
            throw $this->createNotFoundException('No users found in the system.');
        }

        return new Response(
            $this->get('user_service.response_serializer')->serialize(['users' => $users]),
            Response::HTTP_OK
        );
    }

    /**
     * This end point can be used to create a new user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  description="Create a new user",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  requirements={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "requirement"="^[a-zA-Z]*$",
     *          "description"="First name of the user"
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "requirement"="^[a-zA-Z]*$",
     *          "description"="Last name of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "requirement"="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
     *          "description"="a valid email address"
     *      },
     *      {
     *          "name"="country",
     *          "dataType"="string",
     *          "requirement"="^[A-Z]{2}$",
     *          "description"="ISO code of the country eg.:  US or UK"
     *      },
     *      {
     *          "name"="termsAccepted",
     *          "dataType"="boolean",
     *          "requirement"="^a|^b",
     *          "description"="Accepted terms of the website, the value must be 1 for the request to be accepted"
     *      },
     *      {
     *          "name"="accessInfo[password]",
     *          "dataType"="string",
     *          "requirement"="[.]{0,16}",
     *          "description"="the password provided by the user, minimum of 8 digits"
     *      },
     *      {
     *          "name"="mobileNumber[number]",
     *          "dataType"="string",
     *          "requirement"="^(\+\d{1,3}[- ]?)?\d{10}$",
     *          "description"="Mobile number of the user, must be a valid mobile number"
     *      },
     *      {
     *          "name"="mobileNumber[countryCode]",
     *          "dataType"="string",
     *          "requirement"="^[A-Z]{2}$",
     *          "description"="ISO code of the mobile number eg.:  US or UK"
     *      }
     *  },
     *  tags={"stable"},
     *  statusCodes={
     *         201="Returned when the user was successfully created",
     *         400="Bad request: The system is unable to process the request",
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     */
    public function newUserAction(Request $request)
    {
        $requestData = $request->request->all();

        $userService = $this->get('user_service.core');

        $user = $userService->addNewUser($userService->createNewUser(), $requestData);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['user' => $user]),
            Response::HTTP_CREATED
        );
    }

    /**
     * Get all user details by Unique User Identifier (UUID)
     * @param User $user
     * @ParamConverter()
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  description="Get a user by UUID",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
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
     */
    public function getUserAction(User $user)
    {
        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['user' => $user]),
            Response::HTTP_OK
        );
    }

    /**
     * Completely replace an existing user with another user object
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  description="Replace Existing user",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Unique user identifier of the user"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, user is replaced with new details",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     */
    public function putUserAction(Request $request)
    {
        $result=[];
        return $this->render('UserServiceBundle:Default:index.html.twig', $result);
    }

    /**
     * Partially update user details
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  description="Partially update a user",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Unique user identifier of the user"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, user is - partially - successfully updated ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     */
    public function patchUserAction(Request $request)
    {
        $result=[];
        return $this->render('UserServiceBundle:Default:index.html.twig', $result);
    }

    /**
     * Get user mobile phone details
     * @param User $user
     * @ParamConverter()
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a user mobile number details",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Unique user identifier of the user"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user mobile number details are isted",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     */
    public function getUserPhoneNumberAction(User $user)
    {
        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['userPhone' => $user->getMobileNumber()]),
            Response::HTTP_OK
        );
    }
}
