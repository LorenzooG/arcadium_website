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

    response.data.data = response.data.data.map(
      async post =>
        new Post(
          post.id,
          post.title,
          post.description,
          new User(
            post.created_by.id,
            post.created_by.name,
            post.created_by.user_name,
            post.created_by.avatar,
            post.created_by.email
          ),
          new Date(post.created_at),
          new Date(post.updated_at)
        )
    )

    return response.data
  }
}
