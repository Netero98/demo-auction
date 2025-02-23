import { useEffect, useState } from 'react'

export const useGetKpisQuery = () => {
  const [data, setData] = useState<
    | {
        totalExpenses: number
        expensesByCategory: Record<string, number>
        monthlyData: {
          month: string
          revenue: number
          expenses: number
          operationalExpenses: number
          nonOperationalExpenses: number
        }[]
      }[]
    | null
  >(null)

  useEffect(() => {
    // Мокированные данные
    const mockData = [
      {
        totalExpenses: 172000,
        expensesByCategory: {
          Rent: 40000,
          Salaries: 60000,
          Marketing: 20000,
          Utilities: 12000,
          Supplies: 10000,
          Miscellaneous: 30000,
        },
        monthlyData: [
          {
            month: 'January',
            revenue: 20000,
            expenses: 12000,
            operationalExpenses: 8000,
            nonOperationalExpenses: 4000,
          },
          {
            month: 'February',
            revenue: 22000,
            expenses: 14000,
            operationalExpenses: 9000,
            nonOperationalExpenses: 5000,
          },
          {
            month: 'March',
            revenue: 25000,
            expenses: 15000,
            operationalExpenses: 10000,
            nonOperationalExpenses: 5000,
          },
          {
            month: 'April',
            revenue: 23000,
            expenses: 13000,
            operationalExpenses: 9500,
            nonOperationalExpenses: 3500,
          },
          {
            month: 'May',
            revenue: 27000,
            expenses: 16000,
            operationalExpenses: 11000,
            nonOperationalExpenses: 5000,
          },
          {
            month: 'June',
            revenue: 29000,
            expenses: 17000,
            operationalExpenses: 12000,
            nonOperationalExpenses: 5000,
          },
          {
            month: 'July',
            revenue: 31000,
            expenses: 18000,
            operationalExpenses: 12500,
            nonOperationalExpenses: 5500,
          },
          {
            month: 'August',
            revenue: 30000,
            expenses: 17500,
            operationalExpenses: 13000,
            nonOperationalExpenses: 4500,
          },
          {
            month: 'September',
            revenue: 28000,
            expenses: 16000,
            operationalExpenses: 11000,
            nonOperationalExpenses: 5000,
          },
          {
            month: 'October',
            revenue: 26000,
            expenses: 15000,
            operationalExpenses: 10500,
            nonOperationalExpenses: 4500,
          },
          {
            month: 'November',
            revenue: 24000,
            expenses: 14000,
            operationalExpenses: 10000,
            nonOperationalExpenses: 4000,
          },
          {
            month: 'December',
            revenue: 22000,
            expenses: 13000,
            operationalExpenses: 9000,
            nonOperationalExpenses: 4000,
          },
        ],
      },
    ]

    setData(mockData)
  }, [])

  return { data }
}
