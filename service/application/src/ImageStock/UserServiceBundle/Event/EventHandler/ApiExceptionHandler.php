<?php
namespace ImageStock\UserServiceBundle\Event\EventHandler;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HTTPExceptionHandler
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ResponseSerializer constructor.
     * @param SerializerInterface $serializer
     * @param string $format
     */
    public function __construct(SerializerInterface $serializer, String $format)
    {
        $this->format = $format;
        $this->serializer = $serializer;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // get the exception object from the received event
        $exception = $event->getException();
        $message = sprintf(
            'API error : %s ',
            $exception->getMessage()
        );

        $message = $this->serializer->serialize(
            ['error' =>
                [
                    'message' => $message,
                    'code' => $exception->getCode()
                ]
            ],
            $this->format
        );

        $response = new Response();
        $response->setContent($message);
        $response->headers->set('Content-Type', 'application/'.$this->format);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}
