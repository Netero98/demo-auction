Feature: Add wallet

  Scenario:
    Given I am a user
    When I open "/finance/wallet-form" page
    Then I see "Wallet name"
    Then I see "Currency"
    Then I see "Initial balance"
