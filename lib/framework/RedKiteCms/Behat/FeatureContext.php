<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Behat;


use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Implements the feature context used in functional tests
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Behat
 */
class FeatureContext extends MinkContext
{

    /**
     * @Given /^I resize the browser window$/
     */
    public function iMaximizeTheBrowserWindow()
    {
        $this->getSession()->getDriver()->resizeWindow(1280, 768, 'current');
    }

    /**
     * @When /^I click "([^"]*)"$/
     */
    public function iClick($selector)
    {
        $this->findElement($selector)->click();
    }

    private function findElement($selector)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $element = $page->find('css', $selector);
        if (null == $element) {
            throw new ElementNotFoundException($session);
        }

        return $element;
    }

    /**
     * @Then /^I type "([^"]*)" into the element "([^"]*)"$/
     */
    public function iTypeIntoTheElement($text, $selector)
    {
        $script = "jQuery.event.trigger({ type : 'keypress', which : '" . $text . "' });";
        $this->getSession()->evaluateScript($script);

        return;
        $element = $this->findElement($selector);

        $session = $this->getSession();
        $session->getDriver()->keyDown($element, $text);
    }

    /**
     * @Then /^I wait "([^"]*)" seconds until "([^"]*)" is displayed$/
     */
    public function iWaitXSecondsUntilElementIsDisplayed($seconds, $selector)
    {
        $this->getSession()->wait(
            $seconds * 1000,
            sprintf("$('%s').children().length > 0", $selector)
        );
        $this->iShouldSeeTheElement($selector);
    }

    /**
     * @Then /^I should see the element "([^"]*)"$/
     */
    public function iShouldSeeTheElement($selector)
    {
        $this->findElement($selector);
    }

    /**
     * @Then /^I wait until "([^"]*)" is displayed$/
     */
    public function iWaitUntilElementIsDisplayed($selector)
    {
        $this->getSession()->wait(
            5000,
            sprintf("$('%s').children().length > 0", $selector)
        );
        $this->iShouldSeeTheElement($selector);
    }

    /**
     * @Then /^I wait until "([^"]*)" is hidden$/
     */
    public function iWaitUntilElementIsHidden($selector)
    {
        $this->getSession()->wait(
            5000,
            sprintf("$('%s').children().length == 0", $selector)
        );
        $this->iShouldNotSeeTheElement($selector);
    }

    /**
     * @Given /^I should not see the element "([^"]*)"$/
     */
    public function iShouldNotSeeTheElement($selector)
    {
        if ($this->getSession()->evaluateScript(sprintf("return $('%s').is(':visible')==true", $selector))) {
            throw new ExpectationException(sprintf("The %s element should be hidden", $selector), $this->getSession());
        }
    }

    /**
     * @Then /^I wait "([^"]*)" seconds until "([^"]*)" contains "([^"]*)"/
     */
    public function iWaitUntilXSecondsContainsGivenValue($seconds, $selector, $value)
    {
        $this->getSession()->wait(
            $seconds * 1000,
            sprintf("$('%s').val() == '%s'", $selector, $value)
        );
    }

    /**
     * @Then /^I wait until "([^"]*)" contains "([^"]*)"/
     */
    public function iWaitUntilElementContainsGivenValue($selector, $value)
    {
        $this->getSession()->wait(
            5000,
            sprintf("$('%s').val() == '%s'", $selector, $value)
        );
    }

    /**
     * @Then /^I wait "([^"]*)" seconds/
     */
    public function iWaitUntilXSeconds($seconds)
    {
        $this->getSession()->wait($seconds * 1000, true);
    }

    /**
     * @Then /^I move over the element "([^"]*)"/
     */
    public function iMoveOverTheElement($selector)
    {
        $session = $this->getSession();
        $session->getDriver()->mouseOver($session->getSelectorsHandler()->selectorToXpath('css', $selector));
    }

    /**
     * @When /^(?:|I )type in "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function typeIn($selector, $value)
    {
        $el = $this->findElement($selector);
        $el->setValue($value);
    }

    /**
     * @Then /^element "([^"]*)" has not the attribute "([^"]*)" with value "([^"]*)"/
     */
    public function elementHasNotTheAttributeWithValue($selector, $attribute, $value)
    {
        $el = $this->findElement($selector);
        if ($el->hasAttribute($attribute) == $value) {
            throw new ExpectationException(
                sprintf(
                    "The %s element should not contain the attributes %s with value %s",
                    $selector,
                    $attribute,
                    $value
                ), $this->getSession()
            );
        }
    }

    /**
     * @Then /^element "([^"]*)" has the attribute "([^"]*)" with value "([^"]*)"/
     */
    public function elementHasTheAttributeWithValue($selector, $attribute, $value)
    {
        $script = sprintf("$('%s:visible').attr('%s') == '%s'", $selector, $attribute, $value);
        if ($this->getSession()->evaluateScript($script)) {
            throw new ExpectationException(sprintf("The %s element should be hidden", $selector), $this->getSession());
        }

        $el = $this->findElement($selector);
        if ($el->getAttribute($attribute) != $value) {
            throw new ExpectationException(
                sprintf("The %s element should contain the attributes %s with value %s", $selector, $attribute, $value),
                $this->getSession()
            );
        }
    }

    /**
     * @When /^(?:|I )confirm the popup$/
     */
    public function confirmPopup()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }

    /**
     * @When /^(?:|I )cancel the popup$/
     */
    public function cancelPopup()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->dismiss_alert();
    }

    /**
     * @When /^(?:|I )should see "([^"]*)" in popup$/
     *
     * @param string $message The message.
     *
     * @return bool
     */
    public function assertPopupMessage($message)
    {
        return $message == $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();
    }

    /**
     * @When /^(?:|I )fill "([^"]*)" in popup$/
     *
     * @param string $message The message.
     */
    public function setPopupText($message)
    {
        $this->getSession()->getDriver()->getWebDriverSession()->postAlert_text($message);
    }
}