@webUI @insulated
Feature: notes empty state
  As a user
  I want to see a helpful placeholder when no note is selected
  So that I understand how to start

  Background:
    Given these users have been created without skeleton files:
      | username |
      | Alice    |
    And user "Alice" has logged in using the webUI

  Scenario: empty state is shown when the user opens Notes with no notes
    When the user browses to the notes app using the webUI
    Then the notes empty-state placeholder should be displayed on the webUI
    And the notes empty-state heading should be "No note selected"
