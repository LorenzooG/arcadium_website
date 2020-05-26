import Entity from "./Entity";

export type UserConstructor = {
  name: string;
  user_name: string;
  created_at: string;
  updated_at: string;
  is_admin: boolean;
  id: number;
  email: string;
};

export default class User implements Entity {
  public constructor(
    public id: number,
    public name: string,
    public userName: string,
    public email: string,
    public isAdmin: boolean,
    public createdAt: Date,
    public updatedAt: Date
  ) {}

  public static new(data: UserConstructor): User {
    return new User(
      data.id,
      data.name,
      data.user_name,
      data.email,
      data.is_admin,
      new Date(data.created_at),
      new Date(data.updated_at)
    );
  }
}
