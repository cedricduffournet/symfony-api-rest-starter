<?php

/*
 * This file is part of the Dakodapp package.
 *
 * (c) DUFFOURNET Cedric <contact@cedricduffournet.com>
 *
 */

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller used to managed User resource.
 *
 * @author Cedric DUFFOURNET <contact@cedricduffournet.com>
 */
class UserController extends AbstractFOSRestController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Retrives a User.
     *
     * @Rest\Get("/users/me")
     * @Operation(
     *     tags={"User"},
     *     summary="Get user connected info.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=App\Entity\User::class, groups={"Default","user_info"})
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when the User is not found"
     *     )
     * )
     * @Rest\View(serializerGroups={"user_info"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function getConnectedUser(): View
    {
        $user = $this->getUser();

        return $this->view($user, Response::HTTP_OK);
    }
}
