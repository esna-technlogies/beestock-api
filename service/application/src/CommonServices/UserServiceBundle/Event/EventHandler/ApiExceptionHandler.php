<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler;

use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionHandler
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * ResponseSerializer constructor.
     * @param SerializerInterface $serializer
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer, SerializerInterface $serializer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->serializer = $serializer;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if(!preg_match($this->serviceContainer->getParameter("api_url"), $event->getRequest()->getRequestUri(), $matches, PREG_OFFSET_CAPTURE))
        {
            return;
        }

        // get the exception object from the received event
        $exception = $event->getException();
        $message = sprintf(
            'API error : %s ',
            $exception->getMessage()
        );

        $errorResponse = [
                  'message' => $message,
                  'code' => $exception->getCode(),
         ];

        if ($exception instanceOf InvalidFormException )
        {
            $errorResponse['details'] = InvalidFormException::$formErrors;
        }

        $responseContent = $this->serializer->serialize(
            ['error' => $errorResponse],
            $this->serviceContainer->getParameter("api_format")
        );


        $response = new Response();
        $response->setContent($responseContent);


        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}
