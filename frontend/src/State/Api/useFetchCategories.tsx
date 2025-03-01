import { useState } from 'react'
import api from 'src/Api'
import { useAuth } from 'src/Pages/OAuth/Provider'

export const useFetchCategories = () => {
  const [categoriesData, setCategoriesData] = useState<
    { id: string; name: string; user_id: string; created_at: string; updated_at: string }[] | null
  >(null)
  const { getToken } = useAuth()
  const [alreadyFetched, setAlreadyFetched] = useState<boolean>(false)

  const fetchCategoriesInitial = async () => {
    if (!alreadyFetched) {
      setAlreadyFetched(true)

      await fetchCategories()
    }
  }

  const fetchCategories = async () => {
    const response = await api.get('/v1/finance/categories', { Authorization: await getToken() })
    setCategoriesData(response)
    setAlreadyFetched(true)
  }

  return {
    categoriesData,
    fetchCategories,
    fetchCategoriesInitial,
  }
}
