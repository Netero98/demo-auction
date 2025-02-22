Feature: Add wallet

  Scenario: Add wallet
    Given I am a user
    When I open "/finance/wallet-form" page
    Then I see "Wallet name"
    Then I see "Currency"
    Then I see "Initial balance"
    Then I should fill form
    Then I should receive success message
    Then I should check that wallet is added on dashboard
