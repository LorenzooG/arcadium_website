import { AxiosInstance } from 'axios'
import { createApi } from '~/services/api'

export { PostService } from './post.service'

type ServiceClass<T> = {
  new (api: AxiosInstance): T
}

export const getService = <T>(Service: ServiceClass<T>) => {
  return new Service(createApi())
}
