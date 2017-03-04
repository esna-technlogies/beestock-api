<?php

namespace CommonServices\UserServiceBundle\Security\User;

use CommonServices\UserServiceBundle\lib\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * JwtTokenAuthenticator constructor.
     * @param JWTEncoderInterface $jwtEncoder
     * @param UserService $userService
     */
    public function __construct(JWTEncoderInterface $jwtEncoder, UserService $userService)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->userService = $userService;
    }

    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);
        if (!$token) {
            return null;
        }
        return $token;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|object
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->jwtEncoder->decode($credentials);
            $username = $data['username'];
            return $this->userService->getUser(['email' => $username]);

        } catch (JWTDecodeFailureException $e) {

            // TODO: tweak a message for the error according to reason
            // https://github.com/lexik/LexikJWTAuthenticationBundle/blob/05e15967f4dab94c8a75b275692d928a2fbf6d18/Exception/JWTDecodeFailureException.php

            throw new CustomUserMessageAuthenticationException('Validation failure : '.$e->getReason(), null, 401);
        }
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
    }
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @param Request $request
     *   The request that resulted in an AuthenticationException
     *
     * @param AuthenticationException $authException
     *   The exception that started the authentication process
     *
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $responseData = ['error' => 'Unauthorized'];
        if (!is_null($authException)) {
            $responseData['details'] = $authException->getMessage();
        }
        return new JsonResponse($responseData, 401);
    }
}
