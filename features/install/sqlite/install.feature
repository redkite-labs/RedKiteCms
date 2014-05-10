Feature: Installer
  In order to work with RedKite CMS using the sqlite database
  As a website administrator
  I must install the application according with my system configuration

  Background:
    Given I am on "/install"
    And I resize the browser window

  @javascript
  Scenario: The installer form
    Then the "installer_parameters_bundle" field should contain "AcmeWebSiteBundle"
    And the "installer_parameters_driver" field should contain "mysql"
    And the "installer_parameters_host" field should contain "localhost"
    And the "installer_parameters_database" field should contain "redkite"
    And the "installer_parameters_port" field should contain "3306"
    And the "installer_parameters_user" field should contain "root"
    And the "installer_parameters_password_password" field should contain ""
    And the "installer_parameters_password_password_again" field should contain ""
    When I select "postgres" from "installer_parameters_driver"
    And the "installer_parameters_driver" field should contain "pgsql"
    And the "installer_parameters_host" field should contain "localhost"
    And the "installer_parameters_database" field should contain "redkite"
    And the "installer_parameters_port" field should contain "5432"
    And the "installer_parameters_user" field should contain "postgres"
    And the "installer_parameters_password_password" field should contain ""
    And the "installer_parameters_password_password_again" field should contain ""
    When I select "sqlite" from "installer_parameters_driver"
    And the "installer_parameters_driver" field should contain "sqlite"
    And the "installer_parameters_host" field should contain "localhost"
    And the "installer_parameters_database" field should contain "redkite"
    And I should not see the element "#installer_parameters_port"
    And I should not see the element "#installer_parameters_user"
    And I should not see the element "#installer_parameters_password_password"
    And I should not see the element "#installer_parameters_password_password_again"

  @javascript
  Scenario Outline: Deploy bundle must be a valid, registered bundle
    And I should see the element "#installer_parameters_bundle"
    And I fill in "installer_parameters_bundle" with "<value>"
    And I fill in "installer_parameters_website-url" with "http://example.com/"
    When I press "Install"
    Then I should see "Ops, something went wrong"
    And I should see "<message>"

  Examples:
    | value      | message                                               |
    | Fake       | The bundle name must end with Bundle                  |
    | FakeBundle | RedKite CMS requires an existing bundle to work with. |

  @javascript
  Scenario Outline: The website url field must be filled with a valid url
    And I should see the element "#installer_parameters_bundle"
    And I fill in "installer_parameters_website-url" with "<value>"
    When I press "Install"
    Then I should see "Ops, something went wrong"
    And I should see "Website url must start with \"http://\" or \"https://\" and must end with \"/\""

    Examples:
        | value                |
        | something.wrong      |
        | http://example.com   |


  @javascript
  Scenario: RedKite CMS has been installed
    And I should see the element "#installer_parameters_bundle"
    And I select "sqlite" from "installer_parameters_driver"
    And I fill in "installer_parameters_host" with "localhost"
    And I fill in "installer_parameters_database" with "redkite_test"
    And I fill in "installer_parameters_website-url" with "http://example.com/"
    When I press "Install"
    Then I should see "RedKite CMS has been installed!"
    And I should see "http://localhost/rkcms.php/backend/en/index"
    And I should see "http://localhost/rkcms_dev.php/backend/en/index"
    And I should see "The configuration has been written"
    And I should see "Database has been created"
    And I should see "Generated model classes from schema.xml"
    And I should see "1 SQL file has been generated"
    And I should see "All SQL statements have been inserted"
    And I should see "Dumping all rkcms assets"