import { useState, useEffect } from 'react'

export const useGetTransactionsQuery = () => {
  const [data, setData] = useState<
    { id: string; buyer: string; amount: number; productIds: string[] }[] | null
  >(null)

  useEffect(() => {
    const mockData = [
      {
        id: '1',
        buyer: 'John Doe',
        amount: 150,
        productIds: ['101', '102'],
      },
      {
        id: '2',
        buyer: 'Jane Smith',
        amount: 200,
        productIds: ['103'],
      },
      {
        id: '3',
        buyer: 'Alice Brown',
        amount: 350,
        productIds: ['104', '105', '106'],
      },
      {
        id: '4',
        buyer: 'Bob Johnson',
        amount: 120,
        productIds: ['107'],
      },
      {
        id: '5',
        buyer: 'Emma Wilson',
        amount: 500,
        productIds: ['108', '109', '110', '111'],
      },
    ]

    setData(mockData)
  }, [])

  return { data }
}
