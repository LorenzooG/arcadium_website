import Entity from "./Entity";

export type PostConstructor = {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
  description: string;
};

export default class Post implements Entity {
  public constructor(
    public id: number,
    public title: string,
    public description: string,
    public createdAt: Date,
    public updatedAt: Date
  ) {}

  public static new(data: PostConstructor): Post {
    return new Post(
      data.id,
      data.name,
      data.description,
      new Date(data.created_at),
      new Date(data.updated_at)
    );
  }
}
