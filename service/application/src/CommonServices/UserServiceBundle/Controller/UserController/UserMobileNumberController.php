<?php

namespace CommonServices\UserServiceBundle\Controller\UserController;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserMobileNumberController
 * @package CommonServices\UserServiceBundle\Controller\UserController
 */
class UserMobileNumberController extends Controller
{
    /**
     * Get user mobile phone details
     * @param User $user
     * @ParamConverter()
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
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
