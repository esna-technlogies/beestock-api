<?php

namespace CommonServices\UserServiceBundle\Controller\UserController;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 * @package CommonServices\UserServiceBundle\Controller\UserController
 */
class UserController extends Controller
{
    /**
     * This endpoint lists all the users in the system
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
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
     *  section="User Account",
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
     *          "description"="the password provided by the user, minimum of 6 digits"
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

        $user = $userService->createUser($userService->createNewUser(), $requestData);

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
     *  section="User Account",
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
     *
     * @throws NotFoundException
     */
    public function getUserAction(User $user = null)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['user' => $user]),
            Response::HTTP_OK
        );
    }

    /**
     * Completely replace an existing user with another user object
     * @param User $user
     * @param Request $request
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
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
     *  parameters={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "required"="true",
     *          "format"="^[a-zA-Z]*$",
     *          "description"="First name of the user"
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "required"="true",
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Last name of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"="true",
     *          "format"="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
     *          "description"="a valid email address"
     *      },
     *      {
     *          "name"="country",
     *          "dataType"="string",
     *          "required"="true",
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the country eg.:  US or UK"
     *      },
     *      {
     *          "name"="termsAccepted",
     *          "dataType"="boolean",
     *          "required"="true",
     *          "format"="^a|^b",
     *          "description"="Accepted terms of the website, the value must be 1 for the request to be accepted"
     *      },
     *      {
     *          "name"="accessInfo[password]",
     *          "dataType"="string",
     *          "required"="true",
     *          "format"="[.]{0,16}",
     *          "description"="the password provided by the user, minimum of 6 digits"
     *      },
     *      {
     *          "name"="mobileNumber[number]",
     *          "dataType"="string",
     *          "required"="true",
     *          "format"="^(\+\d{1,3}[- ]?)?\d{10}$",
     *          "description"="Mobile number of the user, must be a valid mobile number"
     *      },
     *      {
     *          "name"="mobileNumber[countryCode]",
     *          "dataType"="string",
     *          "required"="true",
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the mobile number eg.:  US or UK"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, user is replaced with new details",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function putUserAction(User $user, Request $request)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }

        $requestData = $request->request->all();

        $userService = $this->get('user_service.core');

        $user = $userService->updateUser($user, $requestData);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['user' => $user]),
            Response::HTTP_OK
        );
    }

    /**
     * Partially update user details
     * @param User $user
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
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
     *  parameters={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "required"="false",
     *          "format"="^[a-zA-Z]*$",
     *          "description"="First name of the user"
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "required"="false",
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Last name of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"="false",
     *          "format"="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
     *          "description"="a valid email address"
     *      },
     *      {
     *          "name"="country",
     *          "dataType"="string",
     *          "required"="false",
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the country eg.:  US or UK"
     *      },
     *      {
     *          "name"="termsAccepted",
     *          "dataType"="boolean",
     *          "required"="false",
     *          "format"="^a|^b",
     *          "description"="Accepted terms of the website, the value must be 1 for the request to be accepted"
     *      },
     *      {
     *          "name"="accessInfo[password]",
     *          "dataType"="string",
     *          "required"="false",
     *          "format"="[.]{0,16}",
     *          "description"="the password provided by the user, minimum of 6 digits"
     *      },
     *      {
     *          "name"="mobileNumber[number]",
     *          "dataType"="string",
     *          "required"="false",
     *          "format"="^(\+\d{1,3}[- ]?)?\d{10}$",
     *          "description"="Mobile number of the user, must be a valid mobile number"
     *      },
     *      {
     *          "name"="mobileNumber[countryCode]",
     *          "dataType"="string",
     *          "required"="false",
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the mobile number eg.:  US or UK"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, user is - partially - successfully updated ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function patchUserAction(User $user, Request $request)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }
        return $this->putUserAction($user, $request);
    }


    /**
     * Delete user by Unique User Identifier (UUID)
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
     *  description="Delete User by UUID. the current version of this endpoint performs a soft delete from the database.",
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
     *         204="Returned when user is successfully deleted ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function deleteUserAction(User $user = null)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }

        $this->get('user_service.core')->deleteUser($user);

        return new Response("User was successfully deleted.",
            Response::HTTP_NO_CONTENT
        );
    }
}
