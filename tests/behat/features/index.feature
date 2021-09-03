@index

Feature: Index

  Scenario: Call index
    When I request "/"
    Then the response code is "200"
