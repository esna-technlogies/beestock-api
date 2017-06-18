<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class LightboxController extends Controller
{
    /**
     * Lists all light-boxes in the system
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="lists all lightboxes in the system ",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  statusCodes={
     *         200="Returned when a list of all light-boxes is returned",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         404={"No light-boxes were found in the system"},
     *         500="The system is unable to process the request server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function listAction()
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }

    /**
     * Lists all light-boxes of a given user
     *
     * @ParamConverter()
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="Lists all light-boxes of a given user ",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="User uuid "
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user light-boxes are listed",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function getUserLightBoxesAction(User $user)
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }

    /**
     * Gets the details of a single light-box given its UUID
     *
     * @ParamConverter()
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="Gets the details of a single light-box given its UUID ",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Lightbox uuid "
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user light-boxes are listed",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function getAction(User $user)
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }
    /**
     * Create a new light-box
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="lists all lightboxes in the system ",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "requirement"="title of the lightbox",
     *          "description"="title of the lightbox "
     *      },
     *      {
     *          "name"="description",
     *          "dataType"="string",
     *          "requirement"="description of the lightbox",
     *          "description"="description of the lightbox"
     *      }
     *  },
     *  statusCodes={
     *         201="Returned when thr lightbox has been successfully created",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         500="The system is unable to process the request server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function newLightBoxAction()
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }

    /**
     * Deletes a light-box given its UUID
     *
     * @ParamConverter()
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="Deletes a light-box given its UUID ",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Lightbox uuid "
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user light-boxes are listed",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function deleteAction(User $user)
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }

    /**
     * Updates the details of a light-box given its UUID
     *
     * @ParamConverter()
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="Updates the details of a light-box given its UUID ",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Lightbox uuid "
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, light-boxes has been updated",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         404={"No light-box with the provided UUID was found"},
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function updateAction(User $user)
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }

    /**
     * Add a new item to a given lightbox
     *
     * @ParamConverter()
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="Add a new item to a given lightbox",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Lightbox uuid "
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, light-boxes has been updated",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         404={"No light-box with the provided UUID was found"},
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function addItemAction(User $user)
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }

    /**
     * Deletes an item from a given lightbox given both UUID of the item and the lightbox
     *
     * @ParamConverter()
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     *  section="Photo Lightbox",
     *  description="Deletes an item from a given lightbox given both UUID of the item and the lightbox ",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Lightbox uuid "
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, light-boxes has been updated",
     *         400="Bad request: The system is unable to process the request",
     *         401="Unauthorized request: user is unauthorized to execute the request",
     *         404={"No light-box with the provided UUID was found"},
     *         500="The system is unable to process the request due to a server side error"
     *  }
     * )
     */
    public function deleteItemAction(User $user)
    {
        return $this->render('PhotoBundle:Default:index.html.twig');
    }
}
