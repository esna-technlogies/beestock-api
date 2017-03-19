# /src/UserServiceBundle/Features/login.feature

@user @registration
Feature: Register user through API

  In order to use the features of the website
  Visitors should be able to register through providing a set of data
  A valid and unique mobile number and email address must e provided

  Background:
    Given there are Users with the following details:

      | id | firstName | lastName | email          | password  | mobile_country| mobile_number |
      | 1  | peter     | Janager  | peter@test.com | testpass  |  EG           | 01008818204   |
      | 2  | john      | Almasry  | john@test.org  | johnpass  |  EG           | 01125639856   |

