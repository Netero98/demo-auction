import { useState, useEffect } from 'react'

export const useGetProductsQuery = () => {
  const [data, setData] = useState<{ id: string; price: number; expense: number }[] | null>(null)

  useEffect(() => {
    // Мокированные данные о продуктах
    const mockProducts = [
      { id: '1', price: 100, expense: 60 },
      { id: '2', price: 150, expense: 90 },
      { id: '3', price: 200, expense: 120 },
      { id: '4', price: 250, expense: 140 },
      { id: '5', price: 300, expense: 180 },
    ]

    setData(mockProducts)
  }, [])

  return { data }
}
