<?php

namespace CommonServices\UserServiceBundle\Controller\ChangeRequest;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class ChangeRequestController
 * @package CommonServices\UserServiceBundle\Controller\UserController
 */
class ChangeRequestController extends Controller
{
    /**
     * Completely replace an existing user with another user object
     * @param ChangeRequest $changeRequest
     * @param Request $request
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Change Request",
     *  description="Verify user change request",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Change request uuid "
     *      },
     *      {
     *          "name"="verificationCode",
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
    public function verifyRequestAction(ChangeRequest $changeRequest, Request $request)
    {
        $verificationCode = $request->request->get('verificationCode');

        $changeRequestService = $this->get('user_service.change_request_domain');

        $changeRequestService->getChangeRequest($changeRequest)->executeChange($verificationCode);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
