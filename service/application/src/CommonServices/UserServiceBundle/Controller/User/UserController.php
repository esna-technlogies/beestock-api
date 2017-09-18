<?php

namespace CommonServices\UserServiceBundle\Controller\User;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\ApiCollectionPagination;

/**
 * Class UserController
 * @package CommonServices\UserServiceBundle\Controller\UserController
 */
class UserController extends Controller
{
    const USER_COLLECTION_LISTING_RESULTS_PER_PAGE = 2;

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
     *         200="Returned when successful, all users are listed",
     *         400="Bad request: The system is unable to process the request",
     *         404="No users were found"
     *  }
     * )
     *
     */
    public function listUsersAction()
    {
        $startPage      = abs(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options'=>['default' => 1 ]]));
        $resultsPerPage = abs(filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT,
            ['options'=>['default' => self::USER_COLLECTION_LISTING_RESULTS_PER_PAGE ]]
        ));

        $resultsHandler =  $this->get('user_service.user_domain')->getUserRepository()->findAllUsers($startPage, $resultsPerPage);

        $resultsPaginator = new ApiCollectionPagination(
            $resultsHandler,
            $this->get('router'),
            'user_service_list_users'
        );

        if (!$resultsPaginator->getResultCollection()) {
            throw $this->createNotFoundException('No users found in the system.');
        }

        $results = $resultsPaginator->getHateoasFriendlyResults('users');

        return new Response(
            $this->get('user_service.response_serializer')->serialize($results),
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
     *  parameters={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="First name of the user"
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Last name of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
     *          "description"="a valid email address"
     *      },
     *      {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="Unicode language identifier (e.g. ar or en_US )",
     *          "description"="a Unicode language identifier (RFC 3066)"
     *      },
     *      {
     *          "name"="country",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the country e.g.: US or GB"
     *      },
     *      {
     *          "name"="termsAccepted",
     *          "dataType"="boolean",
     *          "required"=true,
     *          "format"="^a|^b",
     *          "description"="Accepted terms of the website, the value must be 1 for the request to be accepted"
     *      },
     *      {
     *          "name"="accessInfo[password]",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="[.]{0,16}",
     *          "description"="the password provided by the user, minimum of 6 digits, max 16 digits"
     *      },
     *      {
     *          "name"="mobileNumber[number]",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^(\+\d{1,3}[- ]?)?\d{10}$",
     *          "description"="Mobile number of the user, must be a valid mobile number"
     *      },
     *      {
     *          "name"="mobileNumber[countryCode]",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the mobile number eg.:  US or GB"
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
        $basicAccountInformation = $request->request->all();

        $userDomain = $this->get('user_service.user_domain');

        $user = $userDomain->getDomainService()->createUserAccount($basicAccountInformation);

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
     *  parameters={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "requirement"="true",
     *          "required"= true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="First name of the user"
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Last name of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
     *          "description"="a valid email address"
     *      },
     *      {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="Unicode language identifier (e.g. ar or en_US )",
     *          "description"="a Unicode language identifier (RFC 3066)"
     *      },
     *      {
     *          "name"="country",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the country e.g.: US or GB"
     *      },
     *      {
     *          "name"="termsAccepted",
     *          "dataType"="boolean",
     *          "required"= true,
     *          "format"="^a|^b",
     *          "description"="Accepted terms of the website, the value must be 1 for the request to be accepted"
     *      },
     *      {
     *          "name"="accessInfo[password]",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="[.]{0,16}",
     *          "description"="the password provided by the user, minimum of 6 digits, max 16 digits"
     *      },
     *      {
     *          "name"="mobileNumber[number]",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^(\+\d{1,3}[- ]?)?\d{10}$",
     *          "description"="Mobile number of the user, must be a valid mobile number"
     *      },
     *      {
     *          "name"="mobileNumber[countryCode]",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the mobile number eg.:  US or GB"
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
        $basicUserInformation = $request->request->all();

        $userDomain = $this->get('user_service.user_domain');

        $userManager = $userDomain->getUser($user);

        $userManager->getSettings()->updateAccountBasicSettings($basicUserInformation);

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
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
     *  description="Partially update a user",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
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
     *  parameters={
     *      {
     *          "name"="firstName",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="First name of the user"
     *      },
     *      {
     *          "name"="lastName",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Last name of the user"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$",
     *          "description"="a valid email address"
     *      },
     *      {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="Unicode language identifier (e.g. ar or en_US )",
     *          "description"="a Unicode language identifier (RFC 3066)"
     *      },
     *      {
     *          "name"="country",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the country e.g.: US or GB"
     *      },
     *      {
     *          "name"="termsAccepted",
     *          "dataType"="boolean",
     *          "required"= true,
     *          "format"="^a|^b",
     *          "description"="Accepted terms of the website, the value must be 1 for the request to be accepted"
     *      },
     *      {
     *          "name"="accessInfo[password]",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="[.]{0,16}",
     *          "description"="the password provided by the user, minimum of 6 digits, max 16 digits"
     *      },
     *      {
     *          "name"="mobileNumber[number]",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^(\+\d{1,3}[- ]?)?\d{10}$",
     *          "description"="Mobile number of the user, must be a valid mobile number"
     *      },
     *      {
     *          "name"="mobileNumber[countryCode]",
     *          "dataType"="string",
     *          "required"= true,
     *          "format"="^[A-Z]{2}$",
     *          "description"="ISO code of the mobile number eg.:  US or GB"
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
        return $this->putUserAction($user, $request);
    }

    /**
     * Delete user by Unique User Identifier (UUID)
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
     *  description="Delete User by UUID.",
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

        $user = $this->get('user_service.user_domain')->getUser($user);
        $user->getAccount()->deleteAccount();

        return new Response("", Response::HTTP_NO_CONTENT);
    }


    /**
     * Suspend user (soft delete) by Unique User Identifier (UUID)
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
     *  description="Suspend User by UUID - flags user account as deleted without actually deleting it.",
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
     *         204="Returned when user is successfully suspended ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function suspendUserAction(User $user = null)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }

        $user = $this->get('user_service.user_domain')->getUser($user);
        $user->getAccount()->suspendAccount();

        return new Response("", Response::HTTP_NO_CONTENT);
    }
}
