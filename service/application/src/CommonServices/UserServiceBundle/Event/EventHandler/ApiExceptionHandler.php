<?php

namespace CommonServices\UserServiceBundle\Event\EventHandler;

use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            ' %s .. ',
            $exception->getMessage()
        );

        $errorResponse = [
            'code' => $exception->getCode(),
            'message' => $message
         ];

        $response = new Response();

        if ($exception instanceOf InvalidFormException )
        {
            $errorResponse['details'] = InvalidFormException::$formErrors;
        }

        if ($exception instanceof NotFoundHttpException) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $errorResponse ['message'] = 'Resource not found';
            $errorResponse ['code'] = Response::HTTP_NOT_FOUND;
        } else {
             $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $responseContent = $this->serializer->serialize(
            ['error' => $errorResponse],
            $this->serviceContainer->getParameter("api_format")
        );

        // Send the modified response object to the event
        $response->setContent($responseContent);
        $event->setResponse($response);
    }
}
