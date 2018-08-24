Feature: Edit
  In order to administrate categories
  As an administrator
  I need to be able to change a category

  Background:
    Given I am on the dashboard
    And I have items in the database
    When I follow "Category"

  Scenario: Edit
    Then I follow "Edit"
    And I fill in "Name" with "My Random Category Update"
    And I press "Update"
    Then I should see "successfully updated."

