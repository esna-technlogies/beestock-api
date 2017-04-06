<?php

namespace CommonServices\UserServiceBundle\EventHandler;

use CommonServices\UserServiceBundle\Exception\InvalidFormException;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
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
    private $container;

    /**
     * ResponseSerializer constructor.
     * @param ContainerInterface $container
     * @param SerializerInterface $serializer
     */
    public function __construct(ContainerInterface $container, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->container = $container;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if(!preg_match($this->container->getParameter("api_url"), $event->getRequest()->getRequestUri(), $matches, PREG_OFFSET_CAPTURE))
        {
            return;
        }

        $response = new Response();

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

        if ($exception instanceOf InvalidFormException )
        {
            $errorResponse['details'] = InvalidFormException::$formErrors;
            $errorResponse ['code'] = Response::HTTP_BAD_REQUEST;
        }

        if ($exception instanceof NotFoundHttpException || $exception instanceof NotFoundException) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $errorResponse ['message'] = 'Resource not found';
            $errorResponse ['code'] = Response::HTTP_NOT_FOUND;
        } else {
             $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $responseContent = $this->serializer->serialize(
            ['error' => $errorResponse],
            $this->container->getParameter("api_format")
        );

        // Send the modified response object to the event
        $response->setContent($responseContent);
        $event->setResponse($response);
    }
}
