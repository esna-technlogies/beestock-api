<?php

namespace CommonServices\UserServiceBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ResponseListener
 * @package CommonServices\UserServiceBundle\Event\EventListener
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

        $event->getResponse()->headers->set("Access-Control-Allow-Methods", "GET, POST, PATCH, PUT, DELETE, OPTIONS");
        $event->getResponse()->headers->set("Access-Control-Allow-Origin", "*"); 
    }
}
