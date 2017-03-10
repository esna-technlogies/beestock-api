# /src/UserServiceBundle/Features/login.feature

Feature: Handle user login via the RESTful API

  In order to allow secure access to the system
  As a client software developer
  I need to be able to let users log in and out

  Background:
    Given there are Users with the following details:

      | id | firstName | lastName | email          | password  | mobileCountry| mobileNumber |
      | 1  | peter     | Janager  | peter@test.com | testpass  |  EG          | 01008818204  |
      | 2  | john      | Almasry  | john@test.org  | johnpass  |  EG          | 01125639856  |

  ##   And I add "Content-Type" header equal to "application/json"

  Scenario: User can Login with good credentials
    When  I send a "GET" request to "/api/user" with body:
      """
      {
        "username": "peter",
        "password": "testpass"
      }
      """
    Then the response status code should be 200
    And the response should contain "token"
