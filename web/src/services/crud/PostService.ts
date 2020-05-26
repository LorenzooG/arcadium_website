import CrudService from "./CrudService";

import Post, { PostConstructor } from "../entities/Post";

import getApi from "~/services";

class PostService implements CrudService<Post> {
  public async delete(id: number): Promise<void> {
    const api = getApi();

    await api.delete(`posts/${id}`);
  }

  public async fetch(id: number): Promise<Post> {
    const api = getApi();

    const response = await api.get<PostConstructor>(`posts/${id}`);

    return Post.new(response.data);
  }

  public async fetchAll(): Promise<Post[]> {
    const api = getApi();

    const response = await api.get<PostConstructor[]>("posts");

    return response.data.map(Post.new);
  }

  public async store(content: object): Promise<Post> {
    const api = getApi();

    const response = await api.post<PostConstructor>("posts", content);

    return Post.new(response.data);
  }

  public async update(id: number, content: object): Promise<void> {
    const api = getApi();

    await api.put(`posts/${id}`, content);
  }
}

export default new PostService();
