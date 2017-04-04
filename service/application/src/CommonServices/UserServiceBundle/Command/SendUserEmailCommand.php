<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 03/04/2017
 * Time: 3:20 PM
 */

namespace CommonServices\UserServiceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendUserEmailCommand
 * @package CommonServices\UserServiceBundle\Command
 */
class SendUserEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user-service:send-user-email')
            ->setDescription('Creates a new user.')
            ->setHelp('This command sends password verification messages / emails to users');

        $this
            ->addArgument('message', InputArgument::REQUIRED, 'The message to be sent to the user')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
        ;

    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var  \Aws\Ses\SesClient  $sesServiceClient */
        $sesServiceClient = $this->getContainer()->get('aws.ses');

        $request =[
            'Message' => $input->getArgument('message'),
            'email'   => $input->getArgument('email'),
        ];

        $result = $sesServiceClient->verifyEmailIdentity([
            'EmailAddress' => $input->getArgument('email')
        ]);

        //$sesServiceClient->verifyEmailAddress($request);
    }
}
