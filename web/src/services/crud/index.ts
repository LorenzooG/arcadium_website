import { AxiosInstance } from 'axios'
import { createApi } from '~/services/api'

export { postService } from './post.service'

export type ServiceClass<T> = {
  new (api: AxiosInstance): T
}

export const getService = <T>(Service: ServiceClass<T>) => {
  return new Service(createApi())
}
