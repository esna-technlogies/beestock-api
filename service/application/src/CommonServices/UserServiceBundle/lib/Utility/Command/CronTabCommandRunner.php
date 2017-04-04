<?php

namespace CommonServices\UserServiceBundle\lib\Utility\Command;

use Jobby\Exception;
use \Jobby\Jobby As JobRunner;
use Symfony\Component\Console\Command\Command;

/**
 * Class JobbyCommandWrapper
 * @package CommonServices\UserServiceBundle\lib\Utility\Command
 */
class CronTabCommandRunner
{
    const EVERY_1_MINUTE   = '* * * * *';
    const EVERY_5_MINUTES  = '*/5 * * *';
    const EVERY_15_MINUTES = '*/15 * * *';
    const EVERY_30_MINUTES = '*/30 * * *';
    const EVERY_1_HOUR     = '0 */1 * * *';
    const EVERY_3_HOURS    = '0 */3 * * *';
    const EVERY_6_HOURS    = '0 */6 * * *';
    const EVERY_12_HOURS   = '0 */12 * * *';
    const EVERY_1_DAY      = '0 0 */1 * *';
    const EVERY_1_MONTH    = '0 0 0 */1 *';
    const EVERY_1_YEAR     = '0 0 0 0 */1';
    const EVERY_MID_NIGHT  = '0 0 * * *';

    /**
     * @var JobRunner
     */
    public $runner;

    /**
     * JobbyCommandWrapper constructor.
     */
    public function __construct()
    {
        $this->runner = new JobRunner();
    }

    /**
     * @param Command $commandName
     * @param string $schedule
     * @throws Exception
     */
    public function attachCommand(Command $commandName, string $schedule= '')
    {
        if(empty($schedule)){
            throw new Exception('Invalid command schedule');
        }

        $command = $commandName->getName();

        $this->runner->add(
            $command, [
                // Run a shell commands
                'command'  => './bin/console '.$command,
                'schedule' => $schedule,
                'output'   => '../logs/php-cli/command.log',
                'enabled'  => true,
            ]
        );
    }

    /**
     * @param string $closureName
     * @param \Closure $closure
     * @param string $schedule
     * @throws Exception
     */
    public function attachClosure(string $closureName, \Closure $closure, string $schedule= '')
    {
        if(empty($closureName)){
            throw new Exception('Invalid closure name');
        }
        $this->runner->add(
            $closureName, [
                // Run a shell commands
                'closure'  => $closure,
                'schedule' => $schedule,
                'output'   => '../logs/php-cli/command.log',
                'enabled'  => true,
            ]
        );
    }

    public function run()
    {
        $this->runner->run();
    }
}