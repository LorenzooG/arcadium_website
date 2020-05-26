import CrudService from "~/services/crud/CrudService";

import User, { UserConstructor } from "../entities/User";

import getApi from "~/services";

class UserService implements CrudService<User> {
  public async fetch(id: number): Promise<User> {
    const response = await getApi().get<UserConstructor>(`/users/${id}`);

    return User.new(response.data);
  }

  public async fetchAll(): Promise<User[]> {
    const response = await getApi().get<UserConstructor[]>("/users");

    return response.data.map(User.new);
  }

  public async store(content: object): Promise<User> {
    const response = await getApi().post<UserConstructor>("users", content);

    return User.new(response.data);
  }

  public async delete(id: number): Promise<void> {
    await getApi().delete(`users/${id}`);
  }

  public async update(id: number, content: object): Promise<void> {
    await getApi().put(`users/${id}`, content);
  }

  public async user(token?: string): Promise<User> {
    const emptyHeaders = {};

    const authHeaders = {
      Authorization: `Bearer ${token}`
    };

    const response = await getApi().get<UserConstructor>("/user", {
      headers: token ? authHeaders : emptyHeaders
    });

    return User.new(response.data);
  }
}

export default new UserService();
