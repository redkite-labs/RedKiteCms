Feature: Pages management
  In order to manage the website pages
  As a signed in user
  I must be able to add, edit and delete website pages

  Background:
    Given I am authenticate as "admin" with my password "admin"
    And I resize the browser window
    And The control panel is minimized
    And I am on "/en/index"
    And I should not see the element "#al_panel"
    When I click "#al_open_pages_panel"
    Then I wait until "#al_panel" is displayed


  @javascript
  Scenario: Open pages manager panel
    And I should see the element "#al_panel_closer"
    And I should see "Pages"
    And I should see "Pages selector"
    And I should see the element ".fa-file"
    And I should see "index"
    And I should see the element ".rk-page-language"
    And I should see the element ".rk-page-remover"

  @javascript
  Scenario: Add new page fails because sone required fields are not filled in
    And I select "contact" from "pages_template"
    And I click "#al_page_saver"
    And I wait until "#al_alert" is displayed
    And should see "The name to assign to the page cannot be null. Please provide a valid page name to add your page" in the "#al_alert" element

  @javascript
  Scenario: Add new page
    And I fill in "pages_pageName" with "contacts"
    And I select "contact" from "pages_template"
    And I check "pages_isPublished"
    And I click "[href='#seo']"
    And I fill in "seo_attributes_permalink" with "contact our company"
    And I fill in "seo_attributes_title" with "Stay in touch with our company"
    And I fill in "seo_attributes_description" with "Contact us"
    And I fill in "seo_attributes_keywords" with "some,keywords"
    And I click "[href='#sitemap']"
    And I select "never" from "seo_attributes_sitemapChangeFreq"
    And I select "0.2" from "seo_attributes_sitemapPriority"
    And I click "#al_page_saver"
    And I wait until "#al_alert" is displayed
    And should see "The page has been successfully saved" in the "#al_alert" element

  @javascript
  Scenario: Load page attributes
    And I click ".al_element_selector:first-child"
    And I wait until "#pages_pageName" contains "contacts"
    And the "#pages_template" element should contain "contact"
    And the checkbox "pages_isHome" is not checked
    And the checkbox "pages_isPublished" is checked
    And the "seo_attributes_permalink" field should contain "contact-our-company"
    And the "seo_attributes_description" field should contain "Contact us"
    And the "seo_attributes_keywords" field should contain "some,keywords"
    And the "#seo_attributes_sitemapChangeFreq" element should contain "never"
    And the "#seo_attributes_sitemapPriority" element should contain "0.2"
    And I should see 1 ".al_element_selected" elements
    And the ".al_element_selected" element should contain "contacts"
    And I click ".al_element_selector:first-child"
    And I wait until "#pages_pageName" contains ""
    And the "#pages_template" element should contain ""
    And the "pages_isPublished" checkbox should not be checked
    And the "seo_attributes_permalink" field should contain ""
    And the "seo_attributes_description" field should contain ""
    And the "#seo_attributes_sitemapChangeFreq" element should contain ""
    And the "#seo_attributes_sitemapPriority" element should contain ""
    And I should see 0 ".al_element_selected" elements

  @javascript
  Scenario: Edit page
    And I click ".al_element_selector:first-child"
    And I wait until "#pages_pageName" contains "contacts"
    And I click "[href='#seo']"
    And I fill in "seo_attributes_permalink" with "contact our company to stay in touch"
    And I click "#al_page_saver"
    And I wait until "#al_alert" is displayed
    And should see "The page has been successfully saved" in the "#al_alert" element

  @javascript
  Scenario: Delete a page
    And I click ".rk-page-remover:first-child"
    And I confirm the popup
    And I wait until "#al_alert" is displayed
    And should see "The page has been successfully removed" in the "#al_alert" element

  @javascript
  Scenario: Save as new fails because the page name is the same
    And I click ".al_element_selector:first-child"
    And I wait until "#pages_pageName" contains "index"
    And I select "contact" from "pages_template"
    And I click "#al_page_save_as_new"
    And I wait until "#al_alert" is displayed
    And should see "The web site already contains the page you are trying to add. Please use another name for that page" in the "#al_alert" element

  @javascript
  Scenario: Save as new fails because the page name is the same
    And I click ".al_element_selector:first-child"
    And I wait until "#pages_pageName" contains "index"
    And I fill in "pages_pageName" with "contacts"
    And I select "contact" from "pages_template"
    And I click "#al_page_save_as_new"
    And I wait until "#al_alert" is displayed
    And should see "The page has been successfully saved" in the "#al_alert" element
    And I click ".al_element_selector:first-child"
    And I wait until "#pages_pageName" contains "contacts"
    And the checkbox "pages_isHome" is not checked
    # These next entries will be removed when fixtures will be reset before each feature
    And I click ".rk-page-remover:first-child"
    And I confirm the popup
    And I wait until "#al_alert" is displayed