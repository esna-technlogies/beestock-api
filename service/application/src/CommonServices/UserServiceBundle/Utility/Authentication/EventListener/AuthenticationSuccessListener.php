<?php

namespace CommonServices\UserServiceBundle\Utility\Authentication\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AuthenticationSuccessListener
 * @package CommonServices\UserServiceBundle\Utility\Authentication\EventListener
 */
class AuthenticationSuccessListener {
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @param ContainerInterface $serviceContainer
     * @param RequestStack $requestStack
     */
    public function __construct(ContainerInterface $serviceContainer, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $userService = $this->serviceContainer->get('user_service.user_domain');

        $user = $userService->getUserRepository()->findByUserName($event->getUser()->getUsername());

        $payload = $event->getData();
        $payload['userId'] = $user->getUuid();
        $payload['firstName'] = $user->getFirstName();
        $payload['lastName'] = $user->getLastName();
        $payload['fullName'] = $user->getFullName();
        $payload['language'] = $user->getLanguage();

        $event->setData($payload);
    }
}
