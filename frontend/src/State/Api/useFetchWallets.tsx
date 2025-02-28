import { useState } from 'react'
import api from 'src/Api'
import { useAuth } from 'src/Pages/OAuth/Provider'

export const useFetchWallets = () => {
  const [walletsData, setWalletsData] = useState<
    { id: string; name: string; initial_balance: number; currency: string }[] | null
  >(null)
  const { getToken } = useAuth()
  const [alreadyFetched, setAlreadyFetched] = useState<boolean>(false)

  const fetchWalletsInitial = async () => {
    if (!alreadyFetched) {
      setAlreadyFetched(true)

      await fetchWallets()
    }
  }

  const fetchWallets = async () => {
    const response = await api.get('/v1/finance/wallets', { Authorization: await getToken() })
    setWalletsData(response)
    setAlreadyFetched(true)
  }

  return {
    walletsData,
    fetchWallets,
    fetchWalletsInitial,
  }
}
