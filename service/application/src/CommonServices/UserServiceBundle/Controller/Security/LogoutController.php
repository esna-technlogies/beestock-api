<?php

namespace CommonServices\UserServiceBundle\Controller\Security;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CommonServices\UserServiceBundle\Document\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class LogoutController
 * @package CommonServices\UserServiceBundle\Controller\SecurityController
 */
class LogoutController extends Controller
{
    /**
     * Logs user out of the system
     * @param User $user
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Security",
     *  description="Logs user out of the system",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Unique user identifier of the user"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, user is logged out",
     *         404={"No user with the provided uuid was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function logoutUserAction(User $user)
    {
        //TODO: do something with JWT

        return new Response([],Response::HTTP_NO_CONTENT);
    }
}
