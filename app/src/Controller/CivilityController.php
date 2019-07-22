<?php

namespace App\Controller;

use App\Entity\Civility;
use App\Form\CivilityType;
use App\Service\CivilityServiceInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller used to managed Civility resource.
 *
 * @author Cedric DUFFOURNET <contact@cedricduffournet.com>
 */
class CivilityController extends AbstractFOSRestController
{
    /**
     * @var CivilityServiceInterface
     */
    private $civilityService;

    /**
     * CivilityController constructor.
     */
    public function __construct(CivilityServiceInterface $civilityService)
    {
        $this->civilityService = $civilityService;
    }

    /**
     * Creates a new Civility.
     *
     * @Rest\Post("/api/civilities")
     * @Operation(
     *     tags={"Civility"},
     *     @SWG\Parameter(
     *         name="Request body",
     *         in="body",
     *         description="Civility that need to be added",
     *         required=true,
     *         @Model(type=App\Form\CivilityType::class)
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Returned when created",
     *         @Model(type=App\Entity\Civility::class, groups={"Default","user_info"})
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when the form has errors"
     *     )
     * )
     *
     * @Security("is_granted('ROLE_CIVILITY_CREATE')")
     */
    public function postCivility(Request $request): View
    {
        $civility = $this->civilityService->createCivility();
        $form = $this->createForm(CivilityType::class, $civility);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->civilityService->updateCivility($civility);

            return $this->view($civility, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Retrieves a collection of Civility.
     *
     * @Rest\Get("/public/civilities")
     *
     * @Operation(
     *      tags={"Public"},
     *      summary="Get the list of Civility.",
     *      @SWG\Response(
     *          response="200",
     *          description="Returned when successful",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=App\Entity\Civility::class, groups={"Default","user_info"}))
     *          )
     *     )
     * )
     */
    public function getCivilities(): View
    {
        $civilities = $this->civilityService->getAllCivilities();

        return $this->view($civilities, Response::HTTP_OK);
    }

    /**
     * Retrives a Civility.
     *
     * @Rest\Get("/api/civilities/{civilityId}", requirements={"civilityId"="\d+"})
     * @ParamConverter("civility", options={"id" = "civilityId"})
     * @Operation(
     *     tags={"Civility"},
     *     summary="Get a single Civility.",
     *     @SWG\Parameter(
     *         name="civilityId",
     *         in="path",
     *         description="Civility id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=App\Entity\Civility::class, groups={"Default","user_info"})
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the Civility is not found"
     *     )
     * )
     *
     * @Security("is_granted('ROLE_CIVILITY_VIEW')")
     */
    public function getCivility(Civility $civility): View
    {
        return $this->view($civility, Response::HTTP_OK);
    }

    /**
     * Update an existing Civility.
     *
     * @Rest\Put("/api/civilities/{civilityId}", requirements={"civilityId"="\d+"})
     * @ParamConverter("civility", options={"id" = "civilityId"})
     * @Operation(
     *     tags={"Civility"},
     *     @SWG\Parameter(
     *         name="civilityId",
     *         in="path",
     *         description="Civility id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="Request body",
     *         in="body",
     *         required=true,
     *         @Model(type=App\Form\CivilityType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when updated",
     *         @Model(type=App\Entity\Civility::class, groups={ "Default","user_info"})
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when the form has errors"
     *     )
     * )
     *
     * @Security("is_granted('ROLE_CIVILITY_EDIT')")
     */
    public function putCivility(Request $request, Civility $civility): View
    {
        $form = $this->createForm(CivilityType::class, $civility);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            $this->civilityService->updateCivility($civility);

            return $this->view($civility, Response::HTTP_OK);
        }
        $view = $this->view($form, Response::HTTP_BAD_REQUEST);

        return $view;
    }

    /**
     * Deletes a Civility.
     *
     * @Rest\Delete("/api/civilities/{civilityId}", requirements={"civilityId"="\d+"})
     * @ParamConverter("civility", options={"id" = "civilityId"})
     * @Security("is_granted('ROLE_CIVILITY_DELETE')")
     * @Operation(
     *     tags={"Civility"},
     *     @SWG\Response(
     *         response="204",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the Civility is not found"
     *     )
     * )
     */
    public function deleteCivility(Request $request, Civility $civility): View
    {
        try {
            $this->civilityService->deleteCivility($civility);
        } catch (ForeignKeyConstraintViolationException $e) {
            return $this->view(['message' => 'You can not delete this entity !'], Response::HTTP_BAD_REQUEST);
        }

        return $this->view([], Response::HTTP_NO_CONTENT);
    }
}
