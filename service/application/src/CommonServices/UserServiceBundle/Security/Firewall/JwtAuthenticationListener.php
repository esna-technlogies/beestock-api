<?php

namespace CommonServices\UserServiceBundle\Security\Firewall;

use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\JwtToken;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class JwtAuthenticationListener
 * @package CommonServices\UserServiceBundle\Security\AuthenticationListener
 */
class JwtAuthenticationListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * WsseListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        ContainerInterface $serviceContainer
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @inheritdoc
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->headers->has('x-jwt')) {
            return;
        }

        $jwtToken   = $request->headers->get('x-jwt');

        $jwtHandler = $this->serviceContainer->get('user_service.security.json_web_token_handler');


        try{
            $payload = $jwtHandler->decodeJsonWebToken($jwtToken);

            $unauthenticatedToken = new JwtToken(
                $payload['uuid'],
                $payload['roles'],
                'jwt'
            );

            $authenticatedToken = $this
                ->authenticationManager
                ->authenticate($unauthenticatedToken);

            $this->tokenStorage->setToken($authenticatedToken);

        }catch (\Exception $e){
            throw new InvalidArgumentException( 'Invalid json web token', 403);
        }
    }
}