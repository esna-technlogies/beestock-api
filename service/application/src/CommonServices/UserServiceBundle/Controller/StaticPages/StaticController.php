<?php

namespace CommonServices\UserServiceBundle\Controller\StaticPages;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class StaticController
 * @package CommonServices\UserServiceBundle\Controller\StaticController
 */
class StaticController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Static Resources",
     *  description="Static registration page that renders a static HTML page",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
     *  statusCodes={
     *         200="Page was rendered successfully",
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function signUpAction(Request $request)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl('user_service_post_user')
            )
            ->setMethod('POST');

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        $formParameters = [
            'facebookAccount' => $this->getParameter('facebook_settings'),
            'googleAccount' => $this->getParameter('google_settings'),
            'form' => $form->createView(),
        ];
        return $this->render('UserServiceBundle::sign-up.html.twig', $formParameters);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Static Resources",
     *  description="Static Sign-in page that renders a static HTML page",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
     *  statusCodes={
     *         200="Page was rendered successfully",
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function signInAction(Request $request)
    {

        $formParameters = [
            'facebookAccount' => $this->getParameter('facebook_settings'),
            'googleAccount' => $this->getParameter('google_settings'),
            'signinLink' => "/signin",
            'forgotPasswordLink' => "/forgot-Password"
        ];

        return $this->render('UserServiceBundle::sign-in.html.twig', $formParameters);
    }


}