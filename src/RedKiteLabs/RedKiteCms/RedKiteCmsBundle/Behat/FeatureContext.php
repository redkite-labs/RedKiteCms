<?php
/**
 * Created by PhpStorm.
 * User: alphalemon
 * Date: 26/04/14
 * Time: 9.14
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Behat;


use Behat\Behat\Context\Step\When;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use WebDriver\Exception\ElementNotVisible;

class FeatureContext extends MinkContext implements KernelAwareInterface
{
    /**
     * Kernel.
     *
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I want to authenticate as "([^"]*)" with my password "([^"]*)"$/
     */
    public function iWantToAuthenticateAs($username, $password)
    {
        return array(
            new When('I am on "/login"'),
            new When(sprintf('I fill in "username" with "%s"', $username)),
            new When(sprintf('I fill in "password" with "%s"', $password)),
            new When('I press "Sign in"'),
        );

        $container = $this->kernel->getContainer();
        $session = $container->get('session');

        $firewall = 'red_kite_cms';
        $token = new UsernamePasswordToken('admin', 'admin', $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $driver = $this->getSession()->getDriver();
        $driver->setCookie($cookie);
    }



    /**
     * @Given /^I resize the browser window$/
     */
    public function iMaximizeTheBrowserWindow()
    {
        $this->getSession()->getDriver()->resizeWindow(1280,768,'current');
    }

    /**
     * @When /^I click "([^"]*)"$/
     */
    public function iClick($selector)
    {
        $this->findElement($selector)->click();
    }

    /**
     * @Given /^I should not see the element "([^"]*)"$/
     */
    public function iShouldNotSeeTheElement($selector)
    {
        if ($this->getSession()->evaluateScript(sprintf("return $('%s').is(':visible')", $selector))) {
            throw new ExpectationException(sprintf("The %s element should be hidden", $selector), $this->getSession());
        }
    }

    /**
     * @Then /^I should see the element "([^"]*)"$/
     */
    public function iShouldSeeTheElement($selector)
    {
        $this->findElement($selector);
    }

    /**
     * @Then /^I should see the hidden element "([^"]*)"$/
     */
    public function iShouldSeeTheHiddenElement($selector)
    {
        if ( ! $this->getSession()->evaluateScript(sprintf("return $('%s').is(':visible')", $selector))) {
            throw new ExpectationException(sprintf("The %s element is not visible as expected", $selector), $this->getSession());
        }
    }

    /**
     * @Then /^I wait until "([^"]*)" is displayed$/
     */
    public function iWaitUntilElementIsDisplayed($selector)
    {
        $this->getSession()->wait(5000,
            sprintf("$('%s').children().length > 0", $selector)
        );
    }

    /**
     * @Then /^I wait until "([^"]*)" contains "([^"]*)"/
     */
    public function iWaitUntilElementContainsGivenValue($selector, $value)
    {
        $this->getSession()->wait(5000,
            sprintf("$('%s').val() == '%s'", $selector, $value)
        );
    }

    /**
     * @Given /^The control panel is minimized$/
     */
    public function theControlPanelIsMinimized()
    {
        if ($this->getSession()->evaluateScript("return $('.rk-control-panel-minimized').is(':visible')")) {
            return;
        }

        if ( ! $this->getSession()->evaluateScript("return $('.rk-control-panel-minimized').is(':visible') && $('.rk-control-panel-maximized').is(':visible')")) {
            $el = $this->findElement('.rk-navbar-toggle');
            $el->click();
        }

        if ($this->getSession()->evaluateScript("return $('.rk-control-panel-maximized').is(':visible')")) {
            $el = $this->findElement('.rk-minimize');
            $el->click();
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
}