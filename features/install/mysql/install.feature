Feature: Installer
  In order to work with RedKite CMS using the mysql database
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
  Scenario Outline: An invalid user is provided
    And I should see the element "#installer_parameters_bundle"
    And I select "mysql" from "installer_parameters_driver"
    And I fill in "installer_parameters_host" with "<host>"
    And I fill in "installer_parameters_port" with "<port>"
    And I fill in "installer_parameters_user" with "<user>"
    And I fill in "installer_parameters_password_password" with "<password>"
    And I fill in "installer_parameters_password_password_again" with "<password_again>"
    And I fill in "installer_parameters_website-url" with "http://example.com/"
    When I press "Install"
    Then I should see "Ops, something went wrong"
    Then I should see "<message>" in the ".code" element
  Examples:
    | user | host      | port | password | password_again | message |
    | fake | localhost | 3306 |          |                | It seems that user fake with blank password is not configured on this mysql server |
    | root | ocalhost  | 3306 |          |                | Unknown MySQL server host 'ocalhost' |
    | root | 127.0.0.1 | 5000 |          |                | Can't connect to MySQL server on '127.0.0.1' |
    | root | localhost | 3306 | fake     | fake           | Access denied for user 'root'@'localhost' (using password: YES) |

  @javascript
  Scenario: RedKite CMS has been installed
    And I should see the element "#installer_parameters_bundle"
    And I select "mysql" from "installer_parameters_driver"
    And I fill in "installer_parameters_host" with "localhost"
    And I fill in "installer_parameters_port" with "3306"
    And I fill in "installer_parameters_user" with "root"
    And I fill in "installer_parameters_database" with "redkite_test"
    And I fill in "installer_parameters_password_password" with ""
    And I fill in "installer_parameters_password_password_again" with ""
    And I fill in "installer_parameters_website-url" with "http://example.com/"
    When I press "Install"
    Then I should see "RedKite CMS has been installed!"
    And I should see "http://localhost/rkcms.php/backend/en/index"
    And I should see "http://localhost/rkcms_dev.php/backend/en/index"
    And I should see "The configuration has been written"
    And I should see "Generated model classes from schema.xml"
    And I should see "1 SQL file has been generated"
    And I should see "All SQL statements have been inserted"
    And I should see "Dumping all rkcms assets"