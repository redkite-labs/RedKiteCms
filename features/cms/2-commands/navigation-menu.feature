Feature: User sessions
  In order to access their account
  As a user
  I need to be able to log into the website

  Background:
    Given I want to authenticate as "admin" with my password "admin"

@javascript
Scenario: Open navigation menu
    Given I am on "/en/index"
    And I should not see the element "#rk-cp-nav-button"
    When I click "#rk-navigation-minimized"
    Then I should see the hidden element "#rk-cp-nav-button"

@javascript
Scenario: Open navigation menu 1
    Given I am on "/en/index"
    #And I should not see the element "#rk-cp-nav-button"
    When I click "#rk-start-editor"
    Then I should see the hidden element "#rk-cp-nav-button"

#@javascript
#Scenario: Searching for "behat"
    #Given I am on "/en/index"
#    Given I want to authenticate as "admin" with my password "admin"
#    And I should not see the element "#al_panel"
#    When I click "#al_open_pages_panel"
#    Then I should see the hidden element "#al_panel"















