<?php

namespace CommonServices\UserServiceBundle\EventHandler\User;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Domain\ChangeRequest\ChangeRequestDomain;
use CommonServices\UserServiceBundle\Domain\User\UserDomain;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordChangedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserPasswordRetrievalRequestedEvent;
use CommonServices\UserServiceBundle\Event\User\Password\UserRandomPasswordGeneratedEvent;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use CommonServices\UserServiceBundle\Utility\Security\RandomCodeGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserPasswordRetrievalRequestedListener
 * @package CommonServices\UserServiceBundle\EventHandler\User
 */
class UserPasswordListener implements EventSubscriberInterface
{
    /**
     * @var ChangeRequestDomain
     */
    private $changeRequestsService;
    /**
     * @var UserDomain
     */
    private $userManagerService;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserPasswordChangedListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
        $this->userManagerService    = $this->container->get('user_service.user_domain');
        $this->changeRequestsService = $this->container->get('user_service.change_request_domain');
    }

    /**
     * @param Event $event
     *
     * @throws InvalidArgumentException
     */
    public function onUserPasswordRetrievalRequested(Event $event)
    {
        /** @var UserPasswordRetrievalRequestedEvent $event */
        $userDocument = $event->getUserDocument();

        $user = $this->userManagerService->getUser($userDocument);
        $user->getSecurity()->updatePasswordRetrievalLimits(time());
    }

    /**
     * @param Event $event
     *
     * @throws InvalidArgumentException
     */
    public function onUserPasswordChanged(Event $event)
    {
        /** @var UserPasswordChangedEvent $event */
        $accessInfo = $event->getUserAccessInfo();

        /** @var AccessInfo $accessInfo */
        $accessInfo->setSalt(hash('sha256', time()));
        $userPassword = $accessInfo->getPassword();

        $encoder = $this->container->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($accessInfo, $userPassword);

        $accessInfo->setPassword($encodedPassword);
    }

    /**
     * @param Event $event
     *
     * @throws InvalidArgumentException
     */
    public function onUserRandomPasswordGenerated(Event $event)
    {
        /** @var UserRandomPasswordGeneratedEvent $event */
        $accessInfo = $event->getUserDocument()->getAccessInfo();

        /** @var AccessInfo $accessInfo */
        $userPassword = RandomCodeGenerator::generateRandomVerificationString(6);
        $accessInfo->setPassword($userPassword);

        $this->userManagerService->getUserRepository()->save($event->getUserDocument());

        /// issue a change request event
        $requestLifeTime = 1 * 60 * 60;

        $userService = $this->container->get('user_service.user_domain');
        $userService->getUser($event->getUserDocument())->getAccount()->issueAccountChangeRequest(
            UserRandomPasswordGeneratedEvent::NAME,
            $requestLifeTime,
            'N/A',
            $userPassword
        );
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserPasswordRetrievalRequestedEvent::NAME => array(
                array('onUserPasswordRetrievalRequested', 1),
            ),
            UserPasswordChangedEvent::NAME => array(
                array('onUserPasswordChanged', 1),
            ),
            UserRandomPasswordGeneratedEvent::NAME => array(
                array('onUserRandomPasswordGenerated', 1),
            ),
        );
    }
}