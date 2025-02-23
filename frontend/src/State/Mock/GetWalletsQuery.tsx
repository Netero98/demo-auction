import { useState, useEffect } from 'react'

export const useGetWalletsQuery = () => {
  const [data, setData] = useState<
    { id: string; name: string; balance: number; currency: string }[] | null
  >(null)

  useEffect(() => {
    const mockData: { id: string; name: string; balance: number; currency: string }[] = [
      {
        id: '1',
        name: 'Binance',
        balance: 150,
        currency: 'USD',
      },
      {
        id: '2',
        name: 'Coinbase',
        balance: 200,
        currency: 'EUR',
      },
      {
        id: '3',
        name: 'Kraken',
        balance: 500,
        currency: 'BTC',
      },
      {
        id: '4',
        name: 'MetaMask',
        balance: 1200,
        currency: 'ETH',
      },
      {
        id: '5',
        name: 'Trust Wallet',
        balance: 300,
        currency: 'USDT',
      },
    ]

    setData(mockData)
  }, [])

  return { data }
}
