import api from 'src/Api'

export const getCategories = async (token: string) => {
  return await api.get('/v1/finance/categories', { Authorization: token })
}
