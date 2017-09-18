<?php

namespace CommonServices\PhotoBundle\Controller;

use CommonServices\UserServiceBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CommonServices\UserServiceBundle\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;


class FileStorageController extends Controller
{
    /**
     * Get a new file upload policy for a given user (UUID)
     * @param User $user
     * @ParamConverter()
     * @return Response
     *
     * @ApiDoc(
     *  section="File Storage",
     *  description="Get a new file upload policy for a give user",
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
     *          "description"="Unique user identifier of the user"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful, user details are retrieved ",
     *         400="Bad request: The system is unable to process the request",
     *         404={"No user with the provided UUID was found"},
     *         500="The system is unable to create the user due to a server side error"
     *  }
     * )
     *
     * @throws NotFoundException
     */
    public function uploadPolicyAction(User $user = null)
    {
        if (is_null($user)) {
            throw new NotFoundException("User not found", Response::HTTP_NOT_FOUND);
        }

        $userDomain   = $this->get('user_service.user_domain');
        $userManager  = $userDomain->getUser($user);
        $uploadPolicy = $userManager->getAccount()->newFileUploadPolicy('uploads');

        $formAttributes = $uploadPolicy->getFormAttributes();
        $formInputs     = $uploadPolicy->getFormInputs();

        return new Response(
            $this->get('user_service.response_serializer')
                ->serialize([
                    'fileUploadPolicy' =>[
                        'formAttributes' => $formAttributes,
                        'formInputs'     => $formInputs,
                    ]
                ]),
            Response::HTTP_OK
        );
    }
}
