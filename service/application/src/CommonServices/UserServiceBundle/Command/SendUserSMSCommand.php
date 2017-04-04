<?php

namespace CommonServices\UserServiceBundle\Command;

use CommonServices\UserServiceBundle\Document\ChangeRequest;
use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Event\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\lib\Utility\Aws\SmsSender;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendUserSMSCommand
 * @package CommonServices\UserServiceBundle\Command
 */
class SendUserSMSCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user-service:send-user-sms')
            ->setDescription('Creates a new user.')
            ->setHelp('This command sends password verification messages / emails to users');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $changeRequestService = $this->getContainer()->get('user_service.change_requests_service');
        $userService = $this->getContainer()->get('user_service.core');
        $smsSender   = $this->getContainer()->get('aws.sqs.sms_sender');

        $requests = $changeRequestService->getMostRecentRequests('send_sms');

        foreach ($requests as $request)
        {
            /** @var ChangeRequest $request */
            $uuid = $request->getUuid();
            $eventName = $request->getEventName();
            $verificationCode = $request->getVerificationCode();

            /** @var User $user */
            $user = $userService->getUserByUuid($uuid);

            // TODO : create SMS type checker / parser / verifier
            if(UserPasswordRetrievalRequestedEvent::NAME === $eventName)
            {
                $message = 'Your password reset verification code is :'.PHP_EOL.$verificationCode;
                $smsSender->send(
                    $message,
                    $user->getMobileNumber()->getInternationalNumber()
                );

                $changeRequestService->deleteChangeRequest($request);
            }
        }
    }
}
