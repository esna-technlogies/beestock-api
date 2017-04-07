<?php

namespace CommonServices\UserServiceBundle\Utility\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StandardCommandRunner
 * @package CommonServices\UserServiceBundle\Utility\Command
 */
class StandardCommandRunner
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }

    /**
     * @param ArrayInput $input
     * @param OutputInterface|null $output
     */
    public function execute(ArrayInput $input, OutputInterface $output = null)
    {
        $kernel = $this->serviceContainer->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        if(!$output){
            $output = new NullOutput(
                OutputInterface::VERBOSITY_NORMAL,
                true
            );
        }
        $application->run($input, $output);
    }

}