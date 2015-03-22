Feature: User sessions
  In order to edit the website
  As a registered user
  I need to be able to log into the website

Scenario: User is authenticated
  Given I am on "/login"
  And I resize the browser window
  And I fill in "username" with "admin"
  And I fill in "_password" with "admin"
  When I press "Login"
  Then I should be on "backend/en-gb-homepage"
  And I should see the element "#rkcms-control-panel-btn"

  Given I click "#rkcms-control-panel-btn"
  And I should see the element ".popover"
  When I click "#rkcms-dashboard"
  Then I should be on "backend/dashboard"
  And I click "a > i.fa-file-o"
  And I should be on "backend/page/show"
  And I should see "Pages" in the "h1.page-header" element

  Given I should see the element "#rkcms-add-page-button"
  When I click "#rkcms-add-page-button"
  Then I wait until "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-page-name-input-1] > input" is displayed
  And I should see the element "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-page-name-input-1] > input" 

  Given I type in "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-page-name-input-1] > input" with "our company"
  ## Saves the page name when the input box is left and updates the page name value with the slugified one
  And I click "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-template-name-select-1] > select"
  Then I wait until "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-page-name-input-1] > input" contains "our-company"

  Given I click "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-seo-panel-opener-1] > button"
  And I should see "en_GB" in the "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-seo-row-1] > td[rel=rkcms-seo-panel-language-0]" element
  When I type in "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-seo-row-1] > td[rel=rkcms-seo-panel-permalink-0] > input" with "welcome to redkite labs company site"
  ## Saves the permalink when the input box is left and updates the permalink value with the slugified one
  And I click "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-template-name-select-1] > select"
  Then I wait until "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-seo-row-1] > td[rel=rkcms-seo-panel-permalink-0] > input" contains "welcome-to-redkite-labs-company-site"

  Given I click "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-seo-row-1] > td[rel=rkcms-seo-panel-goto-page-0] > button"
  When I am on "/backend/welcome-to-redkite-labs-company-site"
  ## A back should be enough but it does not work
  Given I click "#rkcms-control-panel-btn"
  And I should see the element ".popover"
  When I click "#rkcms-dashboard"
  Then I should be on "backend/dashboard"
  And I click "a > i.fa-file-o"
  And I should be on "backend/page/show"
  
  Given I should see the element "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1]"
  When I click "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1] > td[rel=rkcms-page-remover-1] > button"
  And I wait until ".bootbox" is displayed
  Then I click "[data-bb-handler=confirm]"
  And I should not see the element "#rkcms-pages-editor-table > div > table > tbody > tr[rel=rkcms-page-row-1]"
  
