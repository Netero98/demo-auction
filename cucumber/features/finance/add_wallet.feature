Feature: Add wallet

  Scenario: Add wallet
    Given I am a user
    When I open "/" page
    Then I see "Wallets"
    Then I see "Wallet name"
    Then I see "Balance"
    Then I see "Currency"
#todo Then I fill the form and see new wallet in dashboard "Wallets" block
