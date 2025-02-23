Feature: Add wallet

  Scenario: Add wallet
    Given I am a user
    When I open "/" page
    Then I see "Wallets"
    Then I see "Wallet name"
    Then I see "Balance"
    Then I see "Currency"
    Then I should fill the form on the same page and submit it
    Then I should see the new wallet added on dashboard
