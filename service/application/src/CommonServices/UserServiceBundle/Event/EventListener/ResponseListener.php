<?php

namespace CommonServices\UserServiceBundle\Event\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ResponseListener
 * @package CommonServices\UserServiceBundle\EventListener
 */
class ResponseListener
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * ResponseListener constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if(preg_match($this->serviceContainer->getParameter("api_url"), $event->getRequest()->getRequestUri(), $matches, PREG_OFFSET_CAPTURE))
        {
            $event->getResponse()->headers->set('Content-Type', 'application/'.$this->serviceContainer->getParameter("api_format"));
        }
    }
}
