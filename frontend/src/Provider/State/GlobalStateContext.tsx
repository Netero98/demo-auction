import { createContext } from 'react'
import Wallet from 'src/State/Model/Wallet'
import Category from 'src/State/Model/Category'
import Transaction from 'src/State/Model/Transaction'

export type GlobalStateContextValue = {
  wallets: Wallet[]
  setWallets: (wallets: Wallet[]) => void
  fetchWallets: () => void
  categories: Category[]
  fetchCategories: () => void
  transactions: Transaction[]
  fetchTransactions: () => void
}

const GlobalStateContext = createContext<GlobalStateContextValue | null>(null)

export default GlobalStateContext
