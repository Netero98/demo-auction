Feature: Wallets dashboard block

  Scenario: Add wallet and see it
    Given I am a user
    When I open "/" page
    Then I see "Wallets"
    Then I see "Wallet name"
    Then I see "Initial balance"
    Then I see "Currency"
    Then I click "button_add_wallet" element
    Then I fill "wallet_name_input" field with "Test wallet name 1"
    Then I fill "wallet_currency_input" field with "USD"
    Then I fill "wallet_initial_balance_input" field with "95534"
    Then I click submit button
#    to be sure that its not the form data
    Then I go to "/" page
    Then I see "Test wallet name 1"
    Then I see "95534"
