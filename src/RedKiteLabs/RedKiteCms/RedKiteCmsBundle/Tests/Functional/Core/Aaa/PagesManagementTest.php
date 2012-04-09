<?php
namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Aaa;

class PagesManagementTest extends \CWebDriverTestCase
{
  protected function setUp()
  {
    $this->setBrowser("*chrome");
    $this->setBrowserUrl("http://alphalemon/");
  }

  public function testMyTestCase()
  {
    // // Opens the panel and checks tha all the required elements exist
    $this->open("/alcms.php/backend");
    $this->click("id=al_open_pages_panel");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ("545" == $this->getElementHeight("id=al_panel")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->assertTrue($this->isElementPresent("id=pages_pageName"));
    $this->assertTrue($this->isElementPresent("id=pages_template"));
    $this->assertTrue($this->isElementPresent("id=pages_isHome"));
    $this->assertTrue($this->isElementPresent("id=pages_isPublished"));
    $this->assertTrue($this->isElementPresent("id=page_attributes_permalink"));
    $this->assertTrue($this->isElementPresent("id=page_attributes_title"));
    $this->assertTrue($this->isElementPresent("id=page_attributes_description"));
    $this->assertTrue($this->isElementPresent("id=page_attributes_keywords"));
    $this->assertTrue($this->isElementPresent("id=al_page_saver"));
    $this->assertTrue($this->isElementPresent("id=page_attributes_idLanguage"));
    $this->assertTrue($this->isElementPresent("css=.al_element_selector"));
    $this->assertTrue($this->isElementPresent("id=al_pages_remover"));
    // // Tries to add a page with a blank form
    $this->click("id=al_page_saver");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->verifyTextPresent("The name to assign to the page cannot be null");
    $this->click("//button[@type='button']");
    // // Tries to add a page giving only the page name
    $this->type("id=pages_pageName", "test page");
    $this->click("id=al_page_saver");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->verifyText("id=al_dialog", "The page requires at least a template");
    $this->click("//button[@type='button']");
    // // Saves the page with the minimum required elements
    $this->select("id=pages_template", "label=home");
    $this->click("id=al_page_saver");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->verifyText("id=al_dialog", "The page has been successfully saved");
    $this->click("//button[@type='button']");
    // // Checks that the added elements exist
    try {
        $this->assertTrue($this->isElementPresent("link=test-page"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    $this->verifyText("id=al_pages_navigator", "index test-page");
    // // Clicks the new page name link on to check that the required class is assigned
    $this->click("link=test-page");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->verifyText("id=al_select_languages_reminder", "And now select the language or remove the page!");
    $this->assertTrue($this->isElementPresent("css=.al_element_selected"));
    $this->click("link=test-page");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->assertFalse($this->isElementPresent("css=.al_element_selected"));
    $this->click("link=test-page");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    // // Edits the page attributes
    $this->select("id=page_attributes_idLanguage", "label=en");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    try {
        $this->assertEquals("test-page", $this->getValue("id=pages_pageName"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    $this->assertEquals("home", $this->getText("id=pages_template"));
    $this->assertEquals("test-page", $this->getValue("id=page_attributes_permalink"));
    $this->type("id=page_attributes_permalink", "test-page added to make a test");
    $this->type("id=page_attributes_title", "The page title");
    $this->type("id=page_attributes_description", "The page description");
    $this->type("id=page_attributes_keywords", "some,fake,keywords");
    $this->type("id=page_attributes_keywords", "some,fake,keywords");
    $this->click("id=al_page_saver");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->verifyText("id=al_dialog", "The page has been successfully saved");
    $this->click("//button[@type='button']");
    $this->click("link=index");
    $this->click("link=test-page");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->assertEquals("test-page-added-to-make-a-test", $this->getValue("id=page_attributes_permalink"));
    $this->assertEquals("The page title", $this->getValue("id=page_attributes_title"));
    $this->assertEquals("The page description", $this->getValue("id=page_attributes_description"));
    $this->assertEquals("some,fake,keywords", $this->getValue("id=page_attributes_keywords"));
    // // Test home page
    $this->click("link=index");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->assertEquals("on", $this->getValue("id=pages_isHome"));
    $this->click("link=test-page");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->click("id=pages_isHome");
    $this->click("id=al_page_saver");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->assertEquals("The page has been successfully saved", $this->getText("id=al_dialog"));
    $this->click("//button[@type='button']");
    $this->click("link=index");
    $this->assertEquals("off", $this->getValue("id=pages_isHome"));
    $this->click("link=test-page");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->assertEquals("on", $this->getValue("id=pages_isHome"));
    // // Test that the home page cannot be deleted
    $this->select("id=page_attributes_idLanguage", "label=");
    $this->click("link=test-page");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->click("id=al_pages_remover");
    $this->assertTrue((bool)preg_match('/^Are you sure to remove the page and its attributes[\s\S]$/',$this->getConfirmation()));
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->assertEquals("It is not allowed to remove the website's home page. Promote another page as the home of your website, then remove this one", $this->getText("id=al_dialog"));
    $this->click("//button[@type='button']");
    // // Assign home page to index again
    $this->click("link=index");
    $this->select("id=page_attributes_idLanguage", "label=en");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->click("id=pages_isHome");
    $this->click("id=al_page_saver");
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->click("link=test-page");
    $this->click("//button[@type='button']");
    // // Delete the page
    $this->select("id=page_attributes_idLanguage", "label=");
    $this->click("link=test-page");
    $this->click("id=al_pages_remover");
    $this->assertTrue((bool)preg_match('/^Are you sure to remove the page and its attributes[\s\S]$/',$this->getConfirmation()));
    $this->waitForCondition("selenium.browserbot.getUserWindow().$.active == 0", "10000");
    $this->click("//button[@type='button']");
    $this->click("id=al_panel_closer");
  }
}
?>