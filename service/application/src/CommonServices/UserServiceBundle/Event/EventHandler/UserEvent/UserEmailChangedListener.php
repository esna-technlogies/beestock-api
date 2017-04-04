<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler\UserEvent;

use CommonServices\UserServiceBundle\Event\UserEmailChangedEvent;
use CommonServices\UserServiceBundle\lib\UserSecurityService;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserEmailChangedListener
 * @package CommonServices\UserServiceBundle\Event\EventHandler\UserEvent
 */
class UserEmailChangedListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * UserEmailChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }

    /**
     * @param Event $event
     */
    public function onUserEmailChanged(Event $event)
    {
        /** @var UserEmailChangedEvent $event */
        $user = $event->getUser();
        $verificationCode = UserSecurityService::generateRandomString(6);
        $commandRunner = $this->serviceContainer->get('user_service.command.standard_command_runner');

        $message ='You recently requested to set your email address to :'.$user->getEmail().PHP_EOL;

        $emailInput = new ArrayInput([
            'command' => 'user-service:send-user-email',
            'message' => $message.PHP_EOL.$verificationCode,
            'email' => $user->getEmail(),
        ]);
        $commandRunner->execute($emailInput);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEmailChangedEvent::NAME => array(
                array('onUserEmailChanged', 1),
            ),
        );
    }
}