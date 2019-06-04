<?php

namespace App\Features\Context;

use Imbo\BehatApiExtension\Context\ApiContext;
use PHPUnit\Framework\Assert as Assertions;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestApiContext.
 */
class RestApiContext extends ApiContext
{
    /**
     * Adds Token to Authentication header for next request.
     *
     * @Given /^I am successfully logged in with username: "([^"]*)", password: "([^"]*)" and grantType: "([^"]*)"$/
     */
    public function iAmSuccessfullyLoggedInWithUsernamePasswordAndGranttype(string $username, string $password, string $grantType): void
    {
        $this->requestOptions = [
                'json' => [
                    'username'   => $username,
                    'password'   => $password,
                    'grant_type' => $grantType,
                ],
        ];
        $this->requestPath('/oauth/v2/proxy', 'POST');
        Assertions::assertEquals(200, $this->response->getStatusCode());
        $responseBody = json_decode($this->response->getBody(), true);
        $this->setRequestHeader('Authorization', 'Bearer '.$responseBody['access_token']);
        // clear request option here, not sure this is the right place
        $this->requestOptions = [];
    }
}
