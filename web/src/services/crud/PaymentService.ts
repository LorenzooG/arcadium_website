import CrudService from "~/services/crud/CrudService";

import Payment, { PaymentConstructor } from "../entities/Payment";

import getApi from "~/services";

type RawItem = {
  product: number;
  amount: number;
};

type ResponseConstructor = {
  id: number;
  original_id: string;
  link: string;
};

export type PaymentResponse = {
  id: number;
  originalId: string;
  link: string;
};

class PaymentService implements CrudService<Payment> {
  public async fetch(id: number): Promise<Payment> {
    const api = getApi();

    const response = await api.get<PaymentConstructor>(`payments/${id}`);

    return Payment.new(response.data);
  }

  public async fetchAll(): Promise<Payment[]> {
    const api = getApi();

    const response = await api.get<PaymentConstructor[]>("payments");

    return response.data.map(Payment.new);
  }

  public async store(): Promise<Payment> {
    throw new Error("Not supported for payments");
  }

  public async update(): Promise<void> {
    throw new Error("Not supported for payments");
  }

  public async delete(): Promise<void> {
    throw new Error("Not supported for payments");
  }

  public async checkout(
    userName: string,
    items: RawItem[],
    paymentMethod = "MP"
  ): Promise<PaymentResponse> {
    const api = getApi();

    const response = await api.post<ResponseConstructor>("/checkout", {
      // eslint-disable-next-line @typescript-eslint/camelcase
      user_name: userName,
      // eslint-disable-next-line @typescript-eslint/camelcase
      payment_type: paymentMethod,
      products: items
    });

    const data = response.data;

    return {
      originalId: data.original_id,
      id: data.id,
      link: data.link
    };
  }
}

export default new PaymentService();
