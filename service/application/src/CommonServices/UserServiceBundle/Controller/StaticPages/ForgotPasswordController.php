<?php

namespace CommonServices\UserServiceBundle\Controller\StaticPages;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ForgotPasswordController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Static Resources",
     *  description="Static forgotpassword page that renders a static HTML page",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
     *  statusCodes={
     *         200="Page was rendered successfully",
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function forgotPasswordAction()
    {
        return $this->render('UserServiceBundle::forgot-password.html.twig');
    }
}
