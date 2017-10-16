<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\UserServiceBundle\Utility\Api\Pagination\ApiCollectionPagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends Controller
{
    const CATEGORY_COLLECTION_LISTING_RESULTS_PER_PAGE = 30;

    /**
     * Lists all categories
     *
     * @ParamConverter()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Category",
     *  description="lists all photo categories in the system",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  filters={
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="limit", "dataType"="integer"}
     *  },
     *  statusCodes={
     *         200="Returned when successful, all categories are listed",
     *         400="Bad request: The system is unable to process the request",
     *         404="No categories were found"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function listAction()
    {
        $startPage      = abs(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options'=>['default' => 1 ]]));
        $resultsPerPage = abs(filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT,
            ['options'=>['default' => self::CATEGORY_COLLECTION_LISTING_RESULTS_PER_PAGE ]]
        ));

        $resultsHandler =  $this->get('photo_service.photo_domain')->getCategoryRepository()->findAllCategories($startPage, $resultsPerPage);

        $resultsPaginator = new ApiCollectionPagination(
            $resultsHandler,
            $this->get('router'),
            'photo_service_list_categories'
        );

        if (!$resultsPaginator->getResultCollection()) {
            throw $this->createNotFoundException('No categories found in the system.');
        }

        $results = $resultsPaginator->getHateoasFriendlyResults('categories');

        return new Response(
            $this->get('user_service.response_serializer')->serialize($results),
            Response::HTTP_OK
        );
    }

    /**
     * Get a random photo in a given category (UUID)
     * @param Category $category
     *
     * @ParamConverter()
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="Photo Category",
     *  description="Get a random photo in a given category",
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
     *         200="Returned when successful, photo details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No photo with the provided UUID was found"},
     *         500="The system is unable to get the photo details due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function getRandomPhotoAction(Category $category = null)
    {
        if (is_null($category)) {
            throw new NotFoundException("Category not found", Response::HTTP_NOT_FOUND);
        }

        $photo = $this->get('photo_service.photo_domain')->getPhotoRepository()->findRandomPhoto($category);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_OK
        );
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

        return new Response("", Response::HTTP_NO_CONTENT);
    }


    /**
     * Completely replace an existing user with another user object
     * @param Category $category
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
     *          "dataType"="photoFile",
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
    public function updateAction(Category $category)
    {
        $photoDomain = $this->get('photo_service.photo_domain');

        $photoDomain->getUser($category);

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['category' => $category]),
            Response::HTTP_OK
        );
    }
}
