<?php

namespace CommonServices\UserServiceBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ChangeRequestsIssuerListener
 * @package CommonServices\UserServiceBundle\Event\EventListener
 */
class ChangeRequestsIssuerListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ResponseListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $userDomain = $this->container->get('user_service.user_domain');

        $userDomain->getDomainService()->processPendingAccountsChanges();
    }
}
