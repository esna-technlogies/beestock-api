<?php

namespace ImageStock\UserServiceBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class ResponseListener
 * @package ImageStock\UserServiceBundle\EventListener
 */
class ResponseListener
{
    /**
     * @var String
     */
    private $apiHeaders;

    /**
     * @var String
     */
    private $documentationPage;

    /**
     * ResponseListener constructor.
     * @param String $apiHeaders
     * @param String $documentationPage
     */
    public function __construct(String $apiHeaders, String $documentationPage)
    {
        $this->documentationPage = $documentationPage;
        $this->apiHeaders = $apiHeaders;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if($event->getRequest()->getPathInfo() === $this->documentationPage){
            return;
        }
        $event->getResponse()->headers->set('Content-Type', 'application/xml');
    }
}
