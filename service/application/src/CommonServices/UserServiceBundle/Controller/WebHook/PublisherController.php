<?php

namespace CommonServices\UserServiceBundle\Controller\WebHook;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountActivatedEvent;
use CommonServices\UserServiceBundle\Event\User\Account\UserAccountSuccessfullyCreatedEvent;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailAddedToAccountEvent;
use CommonServices\UserServiceBundle\Event\User\MobileNumber\UserMobileNumberChangeRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserRandomPasswordGeneratedEvent;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PublisherController
 * @package CommonServices\UserServiceBundle\Controller\UserController
 */
class PublisherController extends Controller
{
    /**
     * This end point runs pending cron jobs (sends emails, SMSs, runs pending processes)
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Web Hooks",
     *  description="This end point runs pending cron jobs (sends emails, SMSs, runs pending processes)",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  statusCodes={
     *         201="Returned when the cron jobs have been run successfully",
     *  }
     * )
     */
    public function sendAction()
    {

        $changeRequestService = $this->get('user_service.change_request_domain');
        $userService = $this->get('user_service.user_domain');
        $smsSender   = $this->get('aws.sqs.sms_sender');
        $emailSender   = $this->get('user_service.email_provider');


        $requests = $changeRequestService->getSearch()->findNotificationRequests();

        /** @var ChangeRequest $request */

        foreach ($requests as $request)
        {
            $uuid = $request->getUser();
            $eventName = $request->getEventName();
            $verificationCode = $request->getVerificationCode();

            /** @var User $user */
            $user = $userService->getUserRepository()->findByUuid($uuid);


            // Change password
            if(UserPasswordRetrievalRequestedEvent::NAME === $eventName)
            {
                $message = 'Your password reset verification code is :'.PHP_EOL.$verificationCode;
                $smsSender->send(
                    $message,
                    $user->getMobileNumber()->getInternationalNumber()
                );

                $verificationUrl = $this->generateUrl(
                    'user_service_execute_verification_request_via_url',
                    ['uuid' => $request->getUuid(), 'code'=>$verificationCode],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $emailSender->send(
                    $user->getEmail(),
                    $user->getFirstName().', '.' Here is how to change your Beestock account password ..',
                    'Account:email.password_change_request.html.twig',
                    [
                        'name' => $user->getFirstName(),
                        'emailAddress' => $user->getEmail(),
                        'verificationCode' => $verificationCode,
                        'verificationUrl' => $verificationUrl,
                    ]
                );
            }


            // Change password
            if(UserRandomPasswordGeneratedEvent::NAME === $eventName)
            {
                $message = 'Your new temporary password is :'.PHP_EOL.$request->getNewValue();
                $smsSender->send(
                    $message,
                    $user->getMobileNumber()->getInternationalNumber()
                );

                $emailSender->send(
                    $user->getEmail(),
                    'Your new account password at Beestock',
                    'Account:email.password_changed.html.twig',
                    [
                        'name' => $user->getFirstName(),
                        'emailAddress' => $user->getEmail(),
                        'newPassword' => $request->getNewValue(),
                    ]
                );
            }



            // Change mobile number

            if(UserMobileNumberChangeRequestedEvent::NAME === $eventName)
            {
                $message = 'Your account verification code is :'.PHP_EOL.$verificationCode;
                $smsSender->send(
                    $message,
                    $user->getMobileNumber()->getInternationalNumber()
                );
            }



            // change email address

            if(UserEmailAddedToAccountEvent::NAME === $eventName)
            {
                $verificationUrl = $this->generateUrl(
                    'user_service_execute_verification_request_via_url',
                    ['uuid' => $request->getUuid(), 'code'=>$verificationCode],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $emailSender->send(
                    $user->getEmail(),
                    $user->getFirstName().', '.' Here is how to verify your new email at Beestock ..',
                    'Account:email.verification.new_email_address.html.twig',
                    [
                        'name' => $user->getFirstName(),
                        'emailAddress' => $user->getEmail(),
                        'verificationCode' => $verificationCode,
                        'verificationUrl' => $verificationUrl,
                    ]
                );
            }



            // Account verification and welcome events

            if(UserAccountSuccessfullyCreatedEvent::NAME === $eventName)
            {
                $userService->getUser($user)->getAccount()->createUserBucket();

                $message = 'Your account activation code is :'.PHP_EOL.$verificationCode;
                $smsSender->send(
                    $message,
                    $user->getMobileNumber()->getInternationalNumber()
                );

                $verificationUrl = $this->generateUrl(
                    'user_service_execute_verification_request_via_url',
                    ['uuid' => $request->getUuid(), 'code'=>$verificationCode],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $emailSender->send(
                    $user->getEmail(),
                    $user->getFirstName().', '.' Here is how to activate your Beestock account ..',
                    'Account:email.verification.upon_registration.html.twig',
                    [
                        'name' => $user->getFirstName(),
                        'emailAddress' => $user->getEmail(),
                        'verificationCode' => $verificationCode,
                        'verificationUrl' => $verificationUrl,
                    ]
                );
            }


            // Account activation and welcome email

            if(UserAccountActivatedEvent::NAME === $eventName)
            {
                $emailSender->send(
                    $user->getEmail(),
                    $user->getFirstName().', '.'Welcome to Beestock !',
                    'Account:registration.welcome.html.twig',
                    [
                        'name' => $user->getFirstName()
                    ]
                );
            }

            $changeRequestService->getChangeRequest($request)->notified();
        }


        //for now temporarily
        $this->cleanAction();

        return new Response("", Response::HTTP_ACCEPTED);
    }

    /**
     * Cleans and removes old and expired pending requests and user notifications
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Web Hooks",
     *  description="Cleans and removes old and expired pending requests and user notifications",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  statusCodes={
     *         201="Returned when the cron jobs have been run successfully",
     *  }
     * )
     */

    public function cleanAction()
    {
        $this->get('user_service.change_request_domain')->getDomainService()->removeExpiredRequests();

        return new Response("", Response::HTTP_ACCEPTED);
    }
}
