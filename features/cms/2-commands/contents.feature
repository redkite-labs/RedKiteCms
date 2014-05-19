Feature: Edit contents on page
  In order to edit contents on a page
  As a signed in user
  I must be able to add, edit and delete blocks on page

  Background:
    Given I am authenticate as "admin" with my password "admin"
    And I resize the browser window

@javascript
Scenario: Move over an editable block shows the interface to edit the content
    Given I am on "/en/index"
    And I should see the element ".rk-start-editor"
    And I should see the element "[data-editor=enabled][data-slot-name][data-type][data-hide-when-edit][data-included][data-block-id][data-name][data-content-editable]"
    And I should see the element "[data-editor=enabled][data-slot-name][data-type][data-hide-when-edit][data-included][data-block-id][data-name][data-html][data-title][rel=popover]"
    And I should not see the element ".al_edit_on"
    When I click ".rk-start-editor"
    Then I should see the hidden element ".al_edit_on"
    And I should not see the element ".al_block_menu_top"
    And I should not see the element ".al_block_menu_bottom"
    And I should not see the element ".al_block_menu_left"
    And I should not see the element ".al_block_menu_right"
    And I should not see the element "#al_block_menu_toolbar"
    And I move over the element "[data-type=Image]:first-child"
    And I should see the hidden element ".al_block_menu_top"
    And I should see the element ".al_block_menu_bottom"
    And I should see the element ".al_block_menu_left"
    And I should see the element ".al_block_menu_right"
    And I should see the element "#al_block_menu_toolbar"

  @javascript
  Scenario: Edit a block handled by popover editor
    Given I am on "/en/index"
    When I click ".rk-start-editor"
    And I should not see the element ".popover"
    # Opens a popover editor
    And I move over the element "[data-type=Image]:first-child"
    Then I click "[data-type=Image]:first-child"
    And I should see the hidden element ".popover"
    # Closes the opened editor
    And I click "[data-type=Image]:first-child"
    And I should not see the element ".popover"

  @javascript
  Scenario: Edit a block handled by inline-text editor
    Given I am on "/en/index"
    And element "[data-slot-name=portfolio_title_box]:first-child" has not the attribute "contenteditable" with value "true"
    And element "[data-slot-name=portfolio_title_box]:first-child" has the attribute "data-texteditor-cfg" with value "standard"
    When I click ".rk-start-editor"
    And element "[data-slot-name=portfolio_title_box]" has the attribute "contenteditable" with value "true"
    Then I move over the element "[data-slot-name=portfolio_title_box]"
    And I should not see the element ".mce-tinymce"
    And I click "[data-slot-name=portfolio_title_box]"
    And I wait until ".mce-tinymce" is displayed
    And I should see the hidden element ".mce-tinymce"
    And I should see the element "[data-slot-name=portfolio_title_box][contenteditable=true][data-texteditor-cfg=standard]"
    # Closes the editor when clicking outside any block
    And I click "#al_cms_contents"
    And I should not see the element ".mce-tinymce"

  @javascript
  Scenario: Add new block above the selected one
    Given I am on "/en/index"
    And I should not see the element ".al-blocks-menu"
    And I should not see the element ".al_block_adder"
    And I should see 1 "[data-slot-name=portfolio_title_box][data-type=Text]" elements
    And the "[data-slot-name=portfolio_title_box]:first-child" element should not contain "This is the default content for a new hypertext block"
    When I click ".rk-start-editor"
    And I move over the element "[data-slot-name=portfolio_title_box]"
    And I click ".al-img-add-top-button"
    Then I should see the hidden element ".al-blocks-menu"
    And I should see the element ".al_block_adder"
    And I click ".al_block_adder[rel=Text]"
    And I wait until "#al_alert" is displayed
    And I should see "The block has been successfully added" in the "#al_alert" element
    And I should see 2 "[data-slot-name=portfolio_title_box][data-type=Text]" elements
    And the "[data-slot-name=portfolio_title_box]:first-child" element should contain "This is the default content for a new hypertext block"

  @javascript
  Scenario: Add new block down the selected one
    Given I am on "/en/index"
    And I should see 2 "[data-slot-name=portfolio_title_box][data-type=Text]" elements
    And the "[data-slot-name=portfolio_title_box]:first-child" element should contain "This is the default content for a new hypertext block"
    When I click ".rk-start-editor"
    And I move over the element "[data-slot-name=portfolio_title_box]:last-child"
    And I click ".al-img-add-bottom-button"
    Then I should see the hidden element ".al-blocks-menu"
    And I should see the element ".al_block_adder"
    And I click ".al_block_adder[rel=Text]"
    And I wait until "#al_alert" is displayed
    And I should see "The block has been successfully added" in the "#al_alert" element
    And I should see 3 "[data-slot-name=portfolio_title_box][data-type=Text]" elements
    And the "[data-slot-name=portfolio_title_box]:last-child" element should contain "This is the default content for a new hypertext block"


  @javascript
  Scenario: Deletes block previously created
    Given I am on "/en/index"
    When I click ".rk-start-editor"
    And I move over the element "[data-slot-name=portfolio_title_box]:first-child"
    And I click ".al-img-delete-button"
    And I confirm the popup
    And I wait until "#al_alert" is displayed
    And I should see "The block has been successfully removed" in the "#al_alert" element
    And I move over the element "[data-slot-name=portfolio_title_box]:last-child"
    And I click ".al-img-delete-button"
    And I confirm the popup
    And I wait until "#al_alert" is displayed
    And I should see "The block has been successfully removed" in the "#al_alert" element