@number

Feature: Number

  Scenario: Check number endpoint
    When I request "trivia"
    Then the response code is "200"
    Then the response body is a JSON array of length 3

  Scenario: Check number endpoint with set number
    When I request "year/2012"
    Then the response code is "200"
    Then the response body is a JSON array of length 3

  Scenario: Check number endpoint with set language
    When I request "math/1?language=de"
    Then the response code is "200"
    Then the response body is a JSON array of length 3
