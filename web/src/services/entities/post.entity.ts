export class Post {
  public constructor(
    public id: number,
    public title: string,
    public description: string,
    public createdAt: Date,
    public updatedAt: Date
  ) {}
}
