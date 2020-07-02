import axios from 'axios'

export const createApi = () => {
  return axios.create({
    baseURL: 'http://localhost',
  })
}
