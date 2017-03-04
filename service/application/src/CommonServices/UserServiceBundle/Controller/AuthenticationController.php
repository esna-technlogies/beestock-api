<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 03/03/2017
 * Time: 3:42 PM
 */

namespace CommonServices\UserServiceBundle\Controller;


use CommonServices\UserServiceBundle\Document\AccessInfo;
use CommonServices\UserServiceBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AuthenticationController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function userLoginAction(Request $request)
    {
        $user = $request->getUser()? $request->getUser() : '';
        /** @var User $user */
        $user = $this->get('user_service.core')->getUser($user);

        if (!$user) {
            throw new NotFoundHttpException('User not fund', null, 401);
        }

        /** @var AccessInfo $accessInfo */
        $accessInfo = $user->getAccessInfo();

        $isValid = $this->get('security.password_encoder')->isPasswordValid($accessInfo, $request->getPassword());

        if (!$isValid) {
            throw new BadCredentialsException();
        }
        $token = $this->get('lexik_jwt_authentication.encoder.default')->encode(
            [
                'username' => $user->getEmail(),
                'roles' => $accessInfo->getRoles()[0]
            ]
        );

        return new JsonResponse(['token' => $token]);
    }
}