import api from 'src/Api'

const getTransactions = async (token: string) => {
  return await api.get('/v1/finance/transactions', { Authorization: token })
}

export default getTransactions
