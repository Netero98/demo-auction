import React, { ReactNode, useMemo, useState, useEffect } from 'react'
import GlobalStateContext, { GlobalStateContextValue } from 'src/Provider/State/GlobalStateContext'
import Wallet from 'src/State/Model/Wallet'
import { getWallets } from 'src/State/Api/WalletRequest'
import { useAuth } from 'src/Pages/OAuth/Provider'
import { getCategories } from 'src/State/Api/CategoryRequest'
import Category from 'src/State/Model/Category'
import Transaction from 'src/State/Model/Transaction'
import getTransactions from 'src/State/Api/TransactionRequest'

type Props = {
  children: ReactNode
}

export default function GlobalStateProvider({ children }: Props) {
  const [wallets, setWallets] = useState<Wallet[]>([])
  const [categories, setCategories] = useState<Category[]>([])
  const [transactions, setTransactions] = useState<Transaction[]>([])
  const getToken = useAuth().getToken

  // todo delete redundant requests
  const fetchWallets = async () => {
    const token = await getToken()
    const response = await getWallets(token)
    setWallets(response)
  }

  // todo delete redundant requests
  const fetchCategories = async () => {
    const token = await getToken()
    const response = await getCategories(token)
    setCategories(response)
  }

  // todo delete redundant requests
  const fetchTransactions = async () => {
    const token = await getToken()
    const response = await getTransactions(token)
    setTransactions(response)
  }

  useEffect(() => {
    fetchWallets().catch((error) => {
      console.error('Failed to fetch wallets:', error)
    })

    fetchCategories().catch((error) => {
      console.error('Failed to fetch categories:', error)
    })

    fetchTransactions().catch((error) => {
      console.error('Failed to fetch transactions:', error)
    })
  }, [])

  const contextValue = useMemo(
    (): GlobalStateContextValue => ({
      wallets,
      setWallets,
      fetchWallets,
      categories,
      fetchCategories,
      transactions,
      fetchTransactions,
    }),
    [wallets, setWallets, fetchWallets, categories, fetchCategories],
  )

  return <GlobalStateContext.Provider value={contextValue}>{children}</GlobalStateContext.Provider>
}
