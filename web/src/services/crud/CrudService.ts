/* eslint-disable semi */
import Entity from "~/services/entities/Entity";

export default interface CrudService<T extends Entity> {
  fetchAll(): Promise<T[]>;
  fetch(id: number): Promise<T>;
  update(id: number, content: object): Promise<void>;
  delete(id: number): Promise<void>;
  store(content: object): Promise<T>;
}
