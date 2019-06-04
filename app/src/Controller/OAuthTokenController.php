<?php

namespace App\Controller;

use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\OAuthServerBundle\Model\Token;
use FOS\OAuthServerBundle\Model\TokenInterface;
use OAuth2\OAuth2;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OAuthTokenController.
 *
 * {@inheritdoc}
 *
 * @Route("/oauth/v2")
 */
class OAuthTokenController extends TokenController
{
    private $clientId;

    private $clientSecret;

    public function __construct(OAuth2 $server, $clientId, $clientSecret)
    {
        parent::__construct($server);
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Get access token.
     *
     *
     * @Route("/token", name="fos_oauth_server_token", methods={"GET", "POST"})
     *
     * @SWG\Post(
     *     consumes={"application/x-www-form-urlencoded"},
     *     tags={"OAuth"},
     *     summary="Get access token.",
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="formData",
     *         description="The client application's identifier",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="client_secret",
     *         in="formData",
     *         description="The client application's secret",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="grant_type",
     *         in="formData",
     *         description="refresh_token|authorization_code|password|client_credentials|custom",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="User name (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="User password (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="refresh_token",
     *         in="formData",
     *         description="The authorization code received by the authorization server(for `refresh_token` grant type`",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="code",
     *         in="formData",
     *         description="The authorization code received by the authorization server (For `authorization_code` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="scope",
     *         in="formData",
     *         description="The scope of the authorization",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="formData",
     *         description="If the `redirect_uri` parameter was included in the authorization request, and their values MUST be identical",
     *         required=false,
     *         type="string"
     *     )
     * )
     * @SWG\Get(
     *     tags={"OAuth"},
     *     summary="Get access token.",
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="query",
     *         description="The client application's identifier",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="client_secret",
     *         in="query",
     *         description="The client application's secret",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="grant_type",
     *         in="query",
     *         description="refresh_token|authorization_code|password|client_credentials|custom",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="username",
     *         in="query",
     *         description="User name (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="refresh_token",
     *         in="query",
     *         description="The authorization code received by the authorization server(for `refresh_token` grant type`",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="code",
     *         in="query",
     *         description="The authorization code received by the authorization server (For `authorization_code` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="scope",
     *         in="query",
     *         description="The scope of the authorization",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="query",
     *         description="If the `redirect_uri` parameter was included in the authorization request, and their values MUST be identical",
     *         required=false,
     *         type="string"
     *     )
     * )
     * @SWG\Response(
     *      response="200",
     *      description="Returned when successful",
     *      @SWG\Schema(type="object",
     *          @SWG\Property(property="access_token", type="string"),
     *          @SWG\Property(property="expires_in", type="integer"),
     *          @SWG\Property(property="token_type", type="string"),
     *          @SWG\Property(property="scope", type="string"),
     *          @SWG\Property(property="refresh_token", type="string")
     *      )
     * )
     */
    public function tokenAction(Request $request): Response
    {
        return parent::tokenAction($request);
    }

    /**
     * Get access token from trusted js application.
     *
     *
     * @return TokenInterface
     *
     * @Route("/proxy", name="fos_oauth_server_proxy", methods={ "POST"})
     *
     * @SWG\Post(
     *     consumes={"application/x-www-form-urlencoded"},
     *     tags={"OAuth"},
     *     summary="Get access token.",
     *     @SWG\Parameter(
     *         name="grant_type",
     *         in="formData",
     *         description="refresh_token|authorization_code|password|client_credentials|custom",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="User name (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="User password (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="refresh_token",
     *         in="formData",
     *         description="The authorization code received by the authorization server(for `refresh_token` grant type`",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="code",
     *         in="formData",
     *         description="The authorization code received by the authorization server (For `authorization_code` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="scope",
     *         in="formData",
     *         description="The scope of the authorization",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="formData",
     *         description="If the `redirect_uri` parameter was included in the authorization request, and their values MUST be identical",
     *         required=false,
     *         type="string"
     *     )
     * )
     * @SWG\Response(
     *      response="200",
     *      description="Returned when successful",
     *      @SWG\Schema(type="object",
     *          @SWG\Property(property="access_token", type="string"),
     *          @SWG\Property(property="expires_in", type="integer"),
     *          @SWG\Property(property="token_type", type="string"),
     *          @SWG\Property(property="scope", type="string"),
     *          @SWG\Property(property="refresh_token", type="string")
     *      )
     * )
     */
    public function proxyAction(Request $request)
    {
        $request->request->add([
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        return parent::tokenAction($request);
    }

    public function getAvailableScope()
    {
    }
}
