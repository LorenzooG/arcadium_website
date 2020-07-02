import { AxiosInstance } from 'axios'
import { Comment } from '~/services/entities'
import { Paginator } from './paginator'
import { createApi } from '~/services/api'

export class CommentService {
  public constructor(private readonly api: AxiosInstance) {}

  public async findAllOfPost(
    post: unknown,
    page = 1
  ): Promise<Paginator<Comment>> {
    const response = await this.api.get<Paginator<any>>(
      `posts/${post}/comments`,
      {
        params: {
          page,
        },
      }
    )

    response.data.data = response.data.data.map(Comment.new)

    return response.data
  }
}

export const commentService = new CommentService(createApi())
