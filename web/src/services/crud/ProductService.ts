import CrudService from "./CrudService";

import Product, { ProductConstructor } from "../entities/Product";

import getApi from "~/services";

class PostService implements CrudService<Product> {
  async delete(id: number): Promise<void> {
    await getApi().delete(`products/${id}`);
  }

  async fetch(id: number): Promise<Product> {
    const api = getApi();

    const response = await api.get<ProductConstructor>(`products/${id}`);

    return Product.new(response.data);
  }

  async fetchAll(): Promise<Product[]> {
    const api = getApi();

    const response = await api.get<ProductConstructor[]>("products");

    return response.data.map(data => Product.new(data));
  }

  async store(content: object): Promise<Product> {
    const api = getApi();

    const response = await api.post<ProductConstructor>("products", content);

    return Product.new(response.data);
  }

  async update(id: number, content: object): Promise<void> {
    await getApi().post(`products/${id}`, content);
  }
}

export default new PostService();
