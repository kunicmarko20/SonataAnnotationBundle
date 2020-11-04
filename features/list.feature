Feature: List
  In order to manipulate categories
  As an administrator
  I need to be able to have a list of all categories

  Background:
    Given I am on the dashboard
    When I follow "Category"

  Scenario: No Results
    Then I should see "Category List"
    And I should see "NO RESULT"

  Scenario: Have Results
    And I have items in the database
    Then I should see "Name"
    And I should see "Edit" button
    And I should see "Delete" button

