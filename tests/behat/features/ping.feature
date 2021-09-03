@ping

Feature: Ping

  Scenario: Check ping
    When I request "ping"
    Then the response code is "200"
