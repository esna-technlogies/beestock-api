<?php

namespace CommonServices\UserServiceBundle\Security\EntryPoint;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * BasicAuthenticationEntryPoint starts an HTTP Basic authentication.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class JwtAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private $realmName;

    public function __construct()
    {
        $this->realmName = 'jwt';
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = new Response();
        $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realmName));
        $response->setStatusCode(401);

        return $response;
    }
}
