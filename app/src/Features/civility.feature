Feature: Provide a consistent standard JSON API endpoint

  In order to offer Civility resource for REST API
  As a software developer
  I need to allow Create, Read, Update, and Delete Civility resources

  Background:
    And there are Groups with the following details:
      | id | name                 | roles                                                                           | superAdmin | 
      | 1  | Super administrateur | ROLE_CIVILITY_VIEW,ROLE_CIVILITY_EDIT,ROLE_CIVILITY_CREATE,ROLE_CIVILITY_DELETE | 1          |
      | 2  | Reader               | ROLE_CIVILITY_VIEW                                                              | 0          |

    And there are Civilities with the following details:
      | id | name     | code |
      | 1  | Monsieur | Mr   |
      | 2  | Madame   | Mme  |

    And there are Users with the following details:
      | id | firstname | lastname  | username            | email               | password  | civilityId | groups               |
      | 1  | fn super  | ln admin  | superadmin@test.com | superadmin@test.com | adminpwd  | 1          | Super administrateur |
      | 2  | fn reader | ls reader | reader@test.com     | reader@test.com     | readerpwd | 1          | Reader               |

    And the "content-type" request header is "application/json"

  Scenario: Can add a new Civility
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When the request body is:
      """
      {
      "name": "New civility",
      "code": "new"
      }
      """
    And I request "/api/civilities" using HTTP POST
    Then the response code is 201
    And the "Content-Type" response header is "application/json"
    And the response body contains JSON:
      """
      {
      "id": 3,
      "name": "New civility",
      "code": "new"
      }
      """

  Scenario: Can get a collection of Civilities
    When I request "/public/civilities" using HTTP GET
    Then the response code is 200
    And the "Content-Type" response header is "application/json"
    And the response body contains JSON:
      """
      [
      {
      "id": 1,
      "name": "Monsieur",
      "code": "Mr"
      },
      {
      "id": 2,
      "name": "Madame",
      "code": "Mme"
      }
      ]
      """

  Scenario: Can get a single Civility
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When I request "/api/civilities/1" using HTTP GET
    Then the response code is 200
    And the "Content-Type" response header is "application/json"
    And the response body contains JSON:
      """
      {
      "id": 1,
      "name": "Monsieur",
      "code": "Mr"
      }
      """

  Scenario: Can update an existing Civility
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When the request body is:
      """
      {
      "name": "Rename a civility",
      "code": "Renamed"
      }
      """
    And I request "/api/civilities/2" using HTTP PUT
    Then the response code is 200
    And the "Content-Type" response header is "application/json"
    And the response body contains JSON:
      """
      {
      "id": 2,
      "name": "Rename a civility",
      "code": "Renamed"
      }
      """

  Scenario: Can delete a Civility
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When I request "/api/civilities/2" using HTTP GET
    Then the response code is 200
    When I request "/api/civilities/2" using HTTP DELETE
    Then the response code is 204
    When I request "/api/civilities/2" using HTTP GET
    Then the response code is 404

  Scenario: Cannot delete a Civility linked to user
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When I request "/api/civilities/1" using HTTP DELETE
    Then the response code is 400

  Scenario: Cannot add a new Civility is code is longer than 10 characters
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When the request body is:
      """
      {
      "name": "New civility",
      "code": "code longer than 10 characters"
      }
      """
    And I request "/api/civilities" using HTTP POST
    Then the response code is 400

  Scenario: Reader cannot add a new Civility
    Given I am successfully logged in with username: "reader@test.com", password: "readerpwd" and grantType: "password"
    And I request "/api/civilities" using HTTP POST
    Then the response code is 403

  Scenario: Cannot create a Civility if not logged in
    When I request "/api/civilities" using HTTP POST
    Then the response code is 401

  Scenario: Cannot get a single Civility if not logged in
    When I request "/api/civilities/1" using HTTP GET
    Then the response code is 401

  Scenario: Cannot update a Civility if not logged in
    When I request "/api/civilities/1" using HTTP PUT
    Then the response code is 401

  Scenario: Cannot delete a Civility if not logged in
    When I request "/api/civilities/1" using HTTP DELETE
    Then the response code is 401

  Scenario: Reader cannot create a Civility if not logged in
    When I request "/api/civilities" using HTTP POST
    Then the response code is 401

  Scenario: Reader cannot update an existing Civility
    Given I am successfully logged in with username: "reader@test.com", password: "readerpwd" and grantType: "password"
    When I request "/api/civilities/1" using HTTP PUT
    Then the response code is 403

  Scenario: Reader cannot delete a Civility
    Given I am successfully logged in with username: "reader@test.com", password: "readerpwd" and grantType: "password"
    When I request "/api/civilities/1" using HTTP DELETE
    Then the response code is 403

  Scenario: Cannot get a Civility that doesn't exist
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When I request "/api/civilities/100" using HTTP GET
    Then the response code is 404

  Scenario: Cannot update a Civility that doesn't exist
    Given I am successfully logged in with username: "superadmin@test.com", password: "adminpwd" and grantType: "password"
    When I request "/api/civilities/100" using HTTP PUT
    Then the response code is 404
