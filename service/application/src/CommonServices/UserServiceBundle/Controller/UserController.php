<?php

namespace CommonServices\UserServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * listUsersAction lists all users in the system
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsersAction()
    {
        $users =  $this->get('user_service.core')->getAllUsers();

        if (!$users) {
            throw $this->createNotFoundException('No users found in the system.');
        }

        return new Response(
            $this->get('user_service.response_serializer')->serialize(['users' => $users]),
            Response::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newUserAction(Request $request)
    {
        $requestData = $request->request->all();

        $user = $this->get('user_service.core')->addNewUser($requestData);

        return new Response(
            $this->get('user_service.response_serializer')->serialize(['user' => $user]),
            Response::HTTP_OK
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserAction(Request $request)
    {
        $result=[];
        return $this->render('UserServiceBundle:Default:index.html.twig', $result);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUserAction(Request $request)
    {
        $result=[];
        return $this->render('UserServiceBundle:Default:index.html.twig', $result);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putUserAction(Request $request)
    {
        $result=[];
        return $this->render('UserServiceBundle:Default:index.html.twig', $result);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchUserAction(Request $request)
    {
        $result=[];
        return $this->render('UserServiceBundle:Default:index.html.twig', $result);
    }
}