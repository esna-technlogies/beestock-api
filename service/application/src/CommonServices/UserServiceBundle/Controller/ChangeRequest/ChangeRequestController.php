<?php

namespace CommonServices\UserServiceBundle\Controller\ChangeRequest;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
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
     * Verify link request handles account changes issued by user (url sent via email)
     * @param ChangeRequest $changeRequest
     * @param Request $request
     *
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
    public function verifyLinkRequestAction(ChangeRequest $changeRequest = null, Request $request)
    {
        $changeRequestService = $this->get('user_service.change_request_domain');

        if (is_null($changeRequest)) {
            return $this->redirect($this->getParameter('cors_allow_origin').'/?redirect_message=ACCOUNT_ACTIVATION_FAILED', 302);
        }

        $changeRequestService->getChangeRequest($changeRequest)->executeChange($request->get('code', ''));

        return $this->redirect($this->getParameter('cors_allow_origin').'/?redirect_message=ACCOUNT_SUCCESSFULLY_ACTIVATED', 302);
    }

/**
     * Verify code request handles account changes issued by user (code sent by SMS and via email)
     * @param ChangeRequest $changeRequest
     * @param Request $request
     *
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
    public function verifyRequestAction(ChangeRequest $changeRequest = null, Request $request)
    {
        $changeRequestService = $this->get('user_service.change_request_domain');

        if (is_null($changeRequest)) {
            throw new NotFoundException("Change request not found", Response::HTTP_NOT_FOUND);
        }

        $changeRequestService->getChangeRequest($changeRequest)->executeChange($request->get('code', ''));

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Activate user given a UUID and activation code (received by email or SMS)
     * @param User $user
     * @param Request $request
     *
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
     *          "description"="UUID of the user to be activated"
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
    public function activateUserRequestAction(User $user, Request $request)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }

        $changeRequestService = $this->get('user_service.change_request_domain');

        $changeRequest = $changeRequestService->getSearch()->findUserActivationRequest($user->getUuid());

        if (is_null($changeRequest)) {
            throw new NotFoundException("User activation request not found", Response::HTTP_NOT_FOUND);
        }

        $changeRequestService->getChangeRequest($changeRequest)->executeChange($request->get('code', ''));

        return new Response('', Response::HTTP_NO_CONTENT);
    }

}
