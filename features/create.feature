Feature: Create
  In order to administrate categories
  As an administrator
  I need to be able to create a category

  Background:
    Given I am on the dashboard
    When I follow "Category"

  Scenario: Create
    And I follow "Add new"
    And I fill in "Name" with "My Random Category"
    And I press "Create"
    Then I should see "successfully created."
