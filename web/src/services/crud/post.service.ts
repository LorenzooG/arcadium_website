import { AxiosInstance } from 'axios'
import { Post } from '~/services/entities'
import { Paginator } from './paginator'
import { createApi } from '~/services/api'

export class PostService {
  public constructor(private readonly api: AxiosInstance) {}

  public async findAll(page = 1): Promise<Paginator<Post>> {
    const response = await this.api.get<Paginator<any>>('posts', {
      params: {
        page,
      },
    })

    response.data.data = response.data.data.map(post => Post.new(post, false))

    return response.data
  }

  public async findOne(postId: number): Promise<Post> {
    const post = await this.api.get(`posts/${postId}`)

    return Post.new(post.data, true)
  }

  public async hasLiked(postId: number): Promise<boolean> {
    try {
      const hasLiked = await this.api.get(`posts/${postId}/liked`)

      return hasLiked.data.value
    } catch {
      // Ignore if has error
    }

    return false
  }

  public async like(postId: number): Promise<void> {
    try {
      await this.api.post(`posts/${postId}/like`)
    } catch {
      // Ignore if has error
    }
  }

  public async unlike(postId: number): Promise<void> {
    try {
      await this.api.get(`posts/${postId}/like`)
    } catch {
      // Ignore if has error
    }
  }
}

export const postService = new PostService(createApi())
