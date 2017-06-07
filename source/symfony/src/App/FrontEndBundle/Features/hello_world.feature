Feature: Hello World

  Scenario: User can open home page
    Given I go to "/"
    #When I wait "5" seconds
    Then I should see "Hello World!"
