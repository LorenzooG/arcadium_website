import { User } from '.'

export class Post {
  public constructor(
    public id: number,
    public title: string,
    public description: string,
    public createdBy: User,
    public createdAt: Date,
    public updatedAt: Date
  ) {}
}
