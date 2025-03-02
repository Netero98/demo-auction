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
    Then I fill "wallet_currency_input" field with "wrong currency"
    Then I fill "wallet_initial_balance_input" field with "95534"
    Then I click "save-wallet-button" element
    Then I see "Invalid currency"
    Then I fill "wallet_currency_input" field with "USD"
    Then I click "save-wallet-button" element
#    to be sure that its not the form data
    Then I go to "/" page
    Then I see "Test wallet name 1"
    Then I see "95534"

  Scenario: Add category and see it
    Given I am a user
    When I open "/" page
    Then I see "Categories"
    Then I see "Category name"
    Then I click "button_add_category" element
    Then I see "Add New Category"
    Then I fill "category_name_input" field with "Cucumber category name 1"
    Then I click "save-category-button" element
    # to be sure that it's not the form data
    Then I go to "/" page
    Then I see "Cucumber category name 1"

#  todo: add feature
#  Scenario: Add transaction and see it
#    Given I am a user
#    When I open "/" page
#    Then I see "Transactions"
#    Then I click "button_add_transaction" element
#    Then I see "Add New Transaction"
#    Then I select "Test wallet name 1" from "transaction_wallet_id" dropdown
#    Then I select "Cucumber category name 1" from "transaction_category_id" dropdown
#    Then I fill "transaction_amount" field with "-12345"
#    Then I fill "transaction_description" field with "Cucumber test add transaction"
#    Then I click "save-transaction-button" element
#    # to be sure that it's not the form data
#    Then I go to "/" page
#    Then I see "Test wallet name 1"
#    Then I see "Cucumber category name 1"
#    Then I see "-12345"
#    Then I see "Cucumber test add transaction"
