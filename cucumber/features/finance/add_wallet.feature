Feature: Wallets dashboard block

  Scenario: Add wallet and see it
    Given I am a user
    When I open "/" page
    Then I see "Wallets"
    Then I see "Wallet name"
    Then I see "Initial balance"
    Then I see "Currency"
    Then I fill "wallet_name_input" field with "Test wallet name 1"
    Then I fill "wallet_currency_input" field with "USD"
    Then I fill "wallet_initial_balance_input" field with "95534"
    Then I click submit button
    Then I fill "wallet_name_input" field with "mock_to_ensure_form_has_different_value"
    Then I fill "wallet_currency_input" field with "mock_to_ensure_form_has_different_value"
    Then I fill "wallet_initial_balance_input" field with "1"
    Then I see "Test wallet name 1"
    Then I see "95534"
