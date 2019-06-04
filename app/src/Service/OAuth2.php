<?php

namespace App\Service;

use App\Entity\Client;
use OAuth2\Model\IOAuth2Client;
use OAuth2\OAuth2 as OAuth2Base;

class OAuth2 extends OAuth2Base
{
    /**
     * @param string|null $scope
     * @param bool        $issue_refresh_token
     *
     * @return array
     */
    public function createAccessToken(
        IOAuth2Client $client,
        $data,
        $scope = null,
        $access_token_lifetime = null,
        $issue_refresh_token = true,
        $refresh_token_lifetime = null
    ) {
        if ($client instanceof Client) {
            $scope = implode(' ', $client->getAllowedScopes());
        }

        return parent::createAccessToken(
            $client,
            $data,
            $scope,
            $access_token_lifetime,
            $issue_refresh_token,
            $refresh_token_lifetime
        );
    }
}
