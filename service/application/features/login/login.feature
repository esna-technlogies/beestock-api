# /src/UserServiceBundle/Features/login.feature

Feature: Handle user login via the RESTful API

  In order to allow secure access to the system
  As a client software developer
  I need to be able to let users log in and out

  Background:
    Given there are Users with the following details:
      | id | username | email          | password |
      | 1  | peter    | peter@test.com | testpass |
      | 2  | john     | john@test.org  | johnpass |
    And I add "Content-Type" header equal to "application/json"

  Scenario: User can Login with good credentials
    When  I send a "POST" request to "/login" with body:
      """
      {
        "username": "peter",
        "password": "testpass"
      }
      """
    Then the response code should be 200
    And the response should contain "token"
