Feature: User sessions
  In order to access their account
  As a user
  I need to be able to log into the website

  Background:
    Given I am authenticate as "admin" with my password "admin"
    And I resize the browser window
    And I am on "/en/index"
    And The control panel is minimized

  @javascript
  Scenario: Add a new page and a new language for testing pourpose
    When I click "#al_open_pages_panel"
    And I wait until "#al_panel" is displayed
    And I fill in "pages_pageName" with "contacts"
    And I select "contact" from "pages_template"
    And I click "#al_page_saver"
    And I wait until "#al_alert" is displayed
    And I should see "The page has been successfully saved" in the "#al_alert" element
    Then  I click "#al_open_languages_panel"
    And I wait until "#al_panel" is displayed
    And I select "Danish" from "languages_language"
    And I click "#al_language_saver"
    And I wait until "#al_alert" is displayed

  @javascript
  Scenario: Open navigation menu
    And I should not see the element "#rk-cp-nav-button"
    And I should not see the element ".rk-pages-navigator-box"
    And I should not see the element "#al_languages_navigator"
    And I should not see the element "#al_pages_navigator"
    When I click "#rk-navigation-full"
    Then I should see the hidden element "#rk-cp-nav-button"
    And I should see the hidden element ".rk-languages-navigator-box"
    And I should see the hidden element ".rk-pages-navigator-box"
    And I should see the hidden element "#al_languages_navigator"
    And the "#al_languages_navigator" element should contain "en"
    And element "#al_languages_navigator" has the attribute "rel" with value "2"
    And I should see the hidden element "#al_pages_navigator"
    And the "#al_pages_navigator" element should contain "index"
    And element "#al_pages_navigator" has the attribute "rel" with value "2"

  @javascript
  Scenario: Change page from navigation menu
    When I click "#rk-navigation-full"
    And I click "#al_languages_navigator"
    And I click ".al_language_item[rel=da]"
    Then I am on "/da/index"

  @javascript
  Scenario: Change page from navigation menu
    When I click "#rk-navigation-full"
    And I click "#al_pages_navigator"
    And I click ".al_page_item[rel=contacts]"
    Then I am on "/en/contacts"

  @javascript
  Scenario: Removes testing page and language
    When I click "#al_open_pages_panel"
    And I wait until "#al_panel" is displayed
    And I click ".rk-page-remover:first-child"
    And I confirm the popup
    And I wait until "#al_alert" is displayed
    And I should see "The page has been successfully removed" in the "#al_alert" element
    Then  I click "#al_open_languages_panel"
    And I wait until "#al_panel" is displayed
    And I click ".rk-language-remover:first-child"
    And I confirm the popup
    And I wait until "#al_alert" is displayed