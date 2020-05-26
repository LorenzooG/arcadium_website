import Entity from "./Entity";

export type ProductConstructor = {
  id: number;
  name: string;
  commands?: string[];
  price: number;
  image: string;
  created_at: string;
  updated_at: string;
  description: string;
};

class Product implements Entity {
  public constructor(
    public id: number,
    public name: string,
    public image: string,
    public description: string,
    public price: number,
    public createdAt: Date,
    public updatedAt: Date,
    public commands?: string[]
  ) {}

  public static new(data: ProductConstructor): Product {
    return new Product(
      data.id,
      data.name,
      data.image,
      data.description,
      data.price,
      new Date(data.created_at),
      new Date(data.updated_at),
      data.commands
    );
  }
}

export default Product;
