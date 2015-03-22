Feature: User sessions
  In order to edit the website
  As a registered user
  I need to be able to log into the website

Scenario: User is not authenticated
  Given I am on "/login"
  And I fill in "username" with "admin"
  And I fill in "_password" with "fake"
  When I press "Login"
  Then I should be on "login"
  And I should see "Bad credentials"

Scenario: User is authenticated
  Given I am on "/login"
  And I fill in "username" with "admin"
  And I fill in "_password" with "admin"
  When I press "Login"
  Then I should be on "backend/en-gb-homepage"
  And I should see the element "#rkcms-control-panel-btn"
