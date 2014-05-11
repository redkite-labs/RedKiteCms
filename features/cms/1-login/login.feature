Feature: User sessions
  In order to edit the website
  As a registered user
  I need to be able to log into the website

Scenario: Login fails
    Given I am authenticate as "admin" with my password "fake"
    Then I should be on "login"
    And I should see "Bad credentials"

Scenario: Login
    Given I am authenticate as "admin" with my password "admin"
    Then I should be on "/en/index"
    And I should see "Welcome back Admin"