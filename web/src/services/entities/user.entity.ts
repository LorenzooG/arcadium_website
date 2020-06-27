export class User {
  public constructor(
    public id: number,
    public name: string,
    public username: string,
    public avatar: string,
    public email?: string
  ) {}
}
