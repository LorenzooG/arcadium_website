import { AxiosInstance } from 'axios'
import { Post, User } from '~/services/entities'
import { Paginator } from './paginator'

type Any = {
  [key: string]: Any
}

export class PostService {
  public constructor(private readonly api: AxiosInstance) {}

  public async findAll(page = 1): Promise<Paginator<Post>> {
    const response = await this.api.get<Paginator<any>>('posts', {
      params: {
        page,
      },
    })

    response.data.data = response.data.data.map(async post => {
      const userResponse = await this.api.get(post.created_by)
      const user = userResponse.data

      return new Post(
        post.id,
        post.title,
        post.description,
        new User(user.id, user.name, user.user_name, user.avatar, user.email),
        new Date(post.created_at),
        new Date(post.updated_at)
      )
    })

    response.data.data = await Promise.all(response.data.data)

    return response.data
  }
}
