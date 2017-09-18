<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\PhotoBundle\Document\Category;
use CommonServices\PhotoBundle\Document\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileAnalysisController extends Controller
{
    /**
     * File analysis endpoint
     * @param Category $category
     * @param Request $request
     * @throws NotFoundException
     *
     * @return Response
     * @internal param User $user
     * @ParamConverter()
     * @ApiDoc(
     *  section="File Analysis",
     *  description="Get uploaded file basic information, thumbnails, and suggested keywords",
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
     *          "name"="url",
     *          "dataType"="string",
     *          "requirement"="^(https?|ftp)://[^\s/$.?#].[^\s]*$",
     *          "description"="Valid file URL"
     *      },
     *      {
     *          "name"="uuid",
     *          "dataType"="string",
     *          "requirement"="Valid V5 UUID",
     *          "description"="File category unique identifier"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     */
    public function infoAction(Category $category, Request $request)
    {
        if (is_null($category)) {
            throw new NotFoundException("Category not found", Response::HTTP_NOT_FOUND);
        }

        $photoDomain   = $this->get('photo_service.photo_domain');

        $url = $request->request->get('url');
        $analysis = $photoDomain->getDomainService()->analyzeFile($url, $category);


        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['fileAnalysis' => $analysis ]),
            Response::HTTP_OK
        );
    }

    /**
     * Get File analysis info by Unique Identifier (UUID) of the file
     * @param File $file
     *
     * @ParamConverter()
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section="File Analysis",
     *  description="Get a file analysis info",
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
     *          "description"="Unique identifier of the file"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, file details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No file with the provided UUID was found"},
     *         500="The system is unable to get the file details due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function getAction(File $file = null)
    {
        if (is_null($file)) {
            throw new NotFoundException("File not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['file' => $file ]),
            Response::HTTP_OK
        );
    }

}
