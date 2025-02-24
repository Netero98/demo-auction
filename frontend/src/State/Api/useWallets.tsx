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

  const fetchWallets = async () => {
    setIsLoading(true)
    setError(null) // очищаем ошибку перед новым запросом
    try {
      const response = await api.get('/v1/finance/wallets', { Authorization: await getToken() })
      setData(response)

      console.table(response)
    } catch (err) {
      setError(await parseError(err as Response | Error))
    } finally {
      setIsLoading(false)
    }
  }

  return { data, error, isLoading, fetchWallets }
}
