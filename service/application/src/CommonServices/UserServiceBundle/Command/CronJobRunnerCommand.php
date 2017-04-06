<?php

namespace CommonServices\UserServiceBundle\Command;

use CommonServices\UserServiceBundle\Utility\Command\CronTabCommandRunner;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CronJobRunnerCommand
 * @package CommonServices\UserServiceBundle\Command
 */
class CronJobRunnerCommand extends ContainerAwareCommand
{
    /**
     * @var CronTabCommandRunner
     */
    public $commandRunner;

    public function __construct($name = null)
    {
        $this->commandRunner = new CronTabCommandRunner();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('cron:run')
            ->setDescription('Runs all scheduled cron jobs')
            ->setHelp('The command all scheduled cron jobs. Place your jobs here to run them as scheduled.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // attach SMS sender command
        $sendEmailCommand = $this->getContainer()->get('user_service.command.send_sms');
        $this->commandRunner->attachCommand($sendEmailCommand, CronTabCommandRunner::EVERY_1_MINUTE);

        // attach SMS email command
        $sendEmailCommand = $this->getContainer()->get('user_service.command.send_email');
        $this->commandRunner->attachCommand($sendEmailCommand, CronTabCommandRunner::EVERY_1_MINUTE);

        /**
         * Run the attached jobs
         */
        $this->commandRunner->run();
    }

}