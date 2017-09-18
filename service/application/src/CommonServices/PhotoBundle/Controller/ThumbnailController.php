<?php
namespace CommonServices\PhotoBundle\Controller;

use CommonServices\PhotoBundle\Document\Photo;
use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class ThumbnailController extends Controller
{
    /**
     * Lists all thumbnails of a given photo
     *
     * @ParamConverter()
     *
     * @param Photo|null $photo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     * @ApiDoc(
     *  section="Photo Thumbnail",
     *  description="lists all thumbnails of a given photo",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  statusCodes={
     *         200="Returned when the thumbnails are successfully retrieved",
     *         400="Bad request: The system is unable to process the request due to the following errors",
     *         500="The system is unable to retrieve the thumbnails due to a server side error"
     *  }
     * )
     *
     */
    public function getThumbnailsAction(Photo $photo = null)
    {
        if (is_null($photo)) {
            throw new NotFoundException("Photo not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_OK
        );
    }


    /**
     * Regenerate all thumbnails of a given photo
     *
     * @ParamConverter()
     *
     * @param Photo|null $photo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     * @ApiDoc(
     *  section="Photo Thumbnail",
     *  description="Regenerates all thumbnails of a given photo",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  statusCodes={
     *         200="Returned when the thumbnails are successfully regenerated",
     *         400="Bad request: The system is unable to process the request due to the following errors",
     *         500="The system is unable to regenerate the thumbnails due to a server side error"
     *  }
     * )
     *
     */
    public function regenerateAction(Photo $photo = null)
    {
        if (is_null($photo)) {
            throw new NotFoundException("Photo not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_OK
        );
    }

    /**
     * Get a custom ratio thumbnail of a given photo
     *
     * @ParamConverter()
     *
     * @param Photo|null $photo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     * @ApiDoc(
     *  section="Photo Thumbnail",
     *  description="Gets a custom ratio thumbnail of a given photo",
     *  output="Symfony\Component\HttpFoundation\Response",
     *  tags={"stable"},
     *  headers={
     *    {
     *        "name"="Authorization",
     *        "description"="Bearer token",
     *    }
     *  },
     *  statusCodes={
     *         200="Returned when the custom ratio thumbnail is successfully retrieved",
     *         400="Bad request: The system is unable to process the request due to the following errors",
     *         500="The system is unable to retrieve the custom ratio thumbnail due to a server side error"
     *  }
     * )
     *
     */
    public function getCustomRatioAction(Photo $photo = null)
    {
        if (is_null($photo)) {
            throw new NotFoundException("Photo not found", Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize(['photo' => $photo]),
            Response::HTTP_OK
        );
    }
}