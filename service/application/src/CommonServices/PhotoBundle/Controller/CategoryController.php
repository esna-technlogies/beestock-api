<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\PhotoBundle\Document\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends Controller
{
    /**
     * Lists all photos in a specific category or with a specific filter criteria
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Category",
     *  description="lists all photos that match a specific criteria",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "required"= true,
     *          "description"="Change Request uuid "
     *      },
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "required"= true,
     *          "requirement"="A string of minimum of 6 digits",
     *          "description"="Verification code received by email or phone"
     *      },
     *  },
     *  statusCodes={
     *         204="Returned when successful, request is verified and change has been made",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No category with the provided UUID was found"},
     *         500="The system is unable to list the categories due to a server side error"
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
     * Get Category by Unique Identifier (UUID)
     * @param Category $category
     *
     * @ParamConverter()
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Category",
     *  description="Get a category by UUID",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Unique identifier of the category"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, category details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No category with the provided UUID was found"},
     *         500="The system is unable to get the category details due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function getAction(Category $category = null)
    {
        if (is_null($category)) {
            throw new NotFoundException("Category not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['category' => $category]),
            Response::HTTP_OK
        );
    }

    /**
     * This end point can be used to create a new user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Category",
     *  description="Create a new category",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  parameters={
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Category title"
     *      },
     *      {
     *          "name"="description",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Category description"
     *      },
     *      {
     *          "name"="file",
     *          "dataType"="file",
     *          "required"=true,
     *          "description"="photo file"
     *      }
     *  },
     *  tags={"stable"},
     *  statusCodes={
     *         201="Returned when the category was successfully created",
     *         400="Bad request: The system is unable to process the request",
     *         500="The system is unable to create the category due to a server side error"
     *  }
     * )
     */
    public function newCategoryAction(Request $request)
    {
        $categoryInfo = $request->request->all();

        $photoDomain = $this->get('photo_service.photo_domain');

        $category = $photoDomain->getDomainService()->createCategory($categoryInfo);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['category' => $category]),
            Response::HTTP_CREATED
        );
    }

    /**
     * Delete a Category by Unique Identifier (UUID)
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Category",
     *  description="Delete Category by UUID.",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  requirements={
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="V5 UUID",
     *          "description"="Unique category identifier (UUID)"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when category is successfully deleted ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No category with the provided UUID was found"},
     *         500="The system is unable to delete the category due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function deleteAction(Category $category = null)
    {
        if (is_null($category)) {
            throw new NotFoundException("Category not found", Response::HTTP_NOT_FOUND);
        }

        $category = $this->get('photo_service.photo_domain')->getCategory($category);

        // implement delete category

        return new Response("", Response::HTTP_NO_CONTENT);
    }


    /**
     * Completely replace an existing user with another user object
     * @param Category $category
     * @param Request $request
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="User Account",
     *  description="Replace Existing user",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"beta"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  parameters={
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Category title"
     *      },
     *      {
     *          "name"="description",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="^[a-zA-Z]*$",
     *          "description"="Category description"
     *      },
     *      {
     *          "name"="file",
     *          "dataType"="file",
     *          "required"=true,
     *          "description"="photo file"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful, user is replaced with new details",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function updateAction(Category $category, Request $request)
    {
        $categoryInfo = $request->request->all();

        $photoDomain = $this->get('photo_service.photo_domain');

        $userManager = $photoDomain->getUser($category);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['category' => $category]),
            Response::HTTP_OK
        );
    }
}
