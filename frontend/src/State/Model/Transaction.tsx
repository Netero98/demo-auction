type Transaction = {
  id: string
  wallet_id: string
  amount: number
  category_id: string
  description: string | null
  created_at: string
  updated_at: string
}

export default Transaction
