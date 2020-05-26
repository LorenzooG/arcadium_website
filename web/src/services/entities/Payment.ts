import Entity from "./Entity";

import { Item } from "~/store/modules/cart/reducer";

import User, { UserConstructor } from "./User";
import Product, { ProductConstructor } from "./Product";

export type ItemConstructor = {
  product: ProductConstructor;
  amount: number;
};

export type PaymentConstructor = {
  id: number;
  payment_raw_response: string;
  payment_response: boolean;
  payment_type: PaymentMethod;
  origin_ip: string;
  delivered: boolean;
  created_at: string;
  updated_at: string;
  total_price: number;
  user_name: string;
  user: UserConstructor;
  products: ItemConstructor[];
};

export enum PaymentMethod {
  MP = "MP"
}

class Payment implements Entity {
  public constructor(
    public id: number,
    public paymentRawResponse: string,
    public originIp: string,
    public isPaid: boolean,
    public paymentMethod: PaymentMethod,
    public isDelivered: boolean,
    public userName: string,
    public user: User,
    public products: Item[],
    public _totalPrice: number,
    public createdAt: Date,
    public updatedAt: Date
  ) {}

  public totalPrice(): number {
    return this._totalPrice;
  }

  public static new(data: PaymentConstructor): Payment {
    return new Payment(
      data.id,
      data.payment_raw_response,
      data.origin_ip,
      data.payment_response,
      data.payment_type,
      data.delivered,
      data.user_name,
      User.new(data.user),
      data.products.map(({ product, amount }) => ({
        product: Product.new(product),
        amount
      })),
      data.total_price,
      new Date(data.created_at),
      new Date(data.updated_at)
    );
  }
}

export default Payment;
