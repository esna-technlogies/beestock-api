# /src/UserServiceBundle/Features/login.feature

@user @login
Feature: Handle user login via the RESTful API

  In order to allow secure access to the system
  As a client software developer
  I need to be able to let users log in and out

  Background:
    Given there are Users with the following details:

      | id | firstName | lastName | email          | password  | mobile_country| mobile_number |
      | 1  | peter     | Janager  | peter@test.com | testpass  |  EG           | 01008818204   |
      | 2  | john      | abbas    | john@test.org  | johnpass  |  EG           | 01125639856   |
      | 2  | salah     | Almasry  | salah@test.org | salahpass |  EG           | 01159986214   |

  ##   And I add "Content-Type" header equal to "application/json"

  Scenario: User can Login with good credentials
    When  I send a "POST" request to "/api/user/login" with body:
      """
      {
        "username": "peter",
        "password": "testpass"
      }
      """
    Then the response status code should be 400

    #And the response should contain "token"
