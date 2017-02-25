<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 28/02/2017
 * Time: 6:30 PM
 */

namespace ImageStock\UserServiceBundle\Controller;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signUpAction(Request $request)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $this->createFormBuilder()
            ->setAction('/app_dev.php/api/user')
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
     */
    public function signInAction()
    {
        $result=[
            'facebookAccount' => $this->getParameter('facebook_settings'),
            'googleAccount' => $this->getParameter('google_settings'),
        ];
        return $this->render('UserServiceBundle::sign-in.html.twig', $result);
    }

}