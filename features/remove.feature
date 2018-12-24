Feature: Remove
  In order to administrate categories
  As an administrator
  I need to be able to remove a category

  Background:
    Given I am on the dashboard
    And the following YAML fixtures where loaded:
    """
    App\Entity\Category:
        category_1:
            name: 'Dummy Category'
    """
    When I follow "Category"

  Scenario: Remove
    Then I follow "Delete"
    And I should see "Confirm deletion"
    And I press "delete"
    Then I should see "deleted successfully."
