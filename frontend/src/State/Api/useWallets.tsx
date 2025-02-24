import { useState } from 'react'
import api, { parseError } from 'src/Api'
import { useAuth } from 'src/OAuth/Provider'

export const useWallets = () => {
  const [data, setData] = useState<
    { id: string; name: string; initial_balance: number; currency: string }[] | null
  >(null)
  const [error, setError] = useState<string | null>(null)
  const [isLoading, setIsLoading] = useState<boolean>(false)
  const { getToken } = useAuth()
  const [alreadyFetched, setAlreadyFetched] = useState<boolean>(false)

  const fetchWalletsInitial = async () => {
    if (!alreadyFetched) {
      setAlreadyFetched(true)

      await syncWallets()
    }
  }

  const syncWallets = async () => {
    setIsLoading(true)
    setError(null)
    try {
      const response = await api.get('/v1/finance/wallets', { Authorization: await getToken() })
      setData(response)
    } catch (err) {
      setError(await parseError(err as Response | Error))
    } finally {
      setIsLoading(false)
    }
  }

  return { data, error, isLoading, fetchWallets: syncWallets, fetchWalletsInitial }
}
