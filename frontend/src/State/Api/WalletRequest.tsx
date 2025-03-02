import api from 'src/Api'

const getWallets = async (token: string) => {
  return await api.get('/v1/finance/wallets', { Authorization: token })
}

export { getWallets }
