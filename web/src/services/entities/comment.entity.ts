import { User } from '.'

export class Comment {
  public constructor(
    public id: number,
    public content: string,
    public createdBy: User,
    public createdAt: string,
    public updatedAt: string
  ) {}

  public static new(comment: any) {
    return new Comment(
      comment.id,
      comment.content,
      new User(
        comment.created_by.id,
        comment.created_by.name,
        comment.created_by.user_name,
        comment.created_by.avatar,
        comment.created_by.email
      ),
      comment.created_at,
      comment.updated_at
    )
  }
}
