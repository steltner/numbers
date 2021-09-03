@404

Feature: 404 Not Found

  Scenario: Check ping
    When I request "dummy"
    Then the response code is 404
