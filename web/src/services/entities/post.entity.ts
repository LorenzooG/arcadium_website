import { User } from '.'

export class Post {
  public constructor(
    public id: number,
    public title: string,
    public description: string,
    public createdBy: User,
    public createdAt: Date,
    public updatedAt: Date,
    public isComplete: boolean
  ) {}

  public static new(post: any, isComplete = true) {
    return new Post(
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
      new Date(post.updated_at),
      isComplete
    )
  }
}
