<?php

namespace CommonServices\UserServiceBundle\Command;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\User\Email\UserEmailChangedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordRetrievalRequestedEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendUserSMSNotificationCommand
 * @package CommonServices\UserServiceBundle\Command
 */
class SendUserSMSNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user-service:send-user-sms-notification')
            ->setDescription('Creates a new user.')
            ->setHelp('This command sends password verification messages / emails to users');
    }

    public function runner(){
        $this->execute(new ArrayInput([]), new BufferedOutput());
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $sendEmailInput, OutputInterface $swiftMailerOutput)
    {
//        $changeRequestService = $this->getContainer()->get('user_service.change_request_domain');
//        $userService = $this->getContainer()->get('user_service.user_domain');
//        $smsSender   = $this->getContainer()->get('aws.sqs.sms_sender');
//
//        $requests = $changeRequestService->getSearch()->getMostRecentRequests();
//
//
//        print "Checking ... SMS".PHP_EOL;
//        var_dump($requests);
//
//        foreach ($requests as $request)
//        {
//
//            print "sending .... ";
//
//            /** @var ChangeRequest $request */
//            $uuid = $request->getUuid();
//            $eventName = $request->getEventName();
//            $verificationCode = $request->getVerificationCode();
//
//            /** @var User $user */
//            $user = $userService->getUserRepository()->findByUuid($uuid);
//
//            // TODO : create SMS type checker / parser / verifier
//            if(UserPasswordRetrievalRequestedEvent::NAME === $eventName)
//            {
//                $message = 'Your password reset verification code is :'.PHP_EOL.$verificationCode;
//                $smsSender->send(
//                    $message,
//                    $user->getMobileNumber()->getInternationalNumber()
//                );
//                $changeRequestService->getChangeRequest($request)->delete();
//            }
//        }
    }
}
