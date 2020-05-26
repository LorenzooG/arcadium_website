import { Product } from "~/services/entities";

import { Item } from "./reducer";

export enum Actions {
  ADD_TO_CART = "@cart/ADD_TO_CART",
  UPDATE_CART = "@cart/UPDATE_CART",
  REMOVE_FROM_CART = "@cart/REMOVE_FROM_CART",
  CHECKOUT_REQUEST = "@cart/CHECKOUT_REQUEST",
  CHECKOUT_SUCCESS = "@cart/CHECKOUT_SUCCESS",
  CHECKOUT_FAIL = "@cart/CHECKOUT_FAIL",
  CLEAR = "@cart/CLEAR"
}

export function addToCartAction(product: Product): AddToCartAction {
  return {
    type: Actions.ADD_TO_CART,
    payload: {
      product
    }
  };
}

export function updateCartAction(
  product: Product,
  amount: number
): UpdateCartAction {
  return {
    type: Actions.UPDATE_CART,
    payload: {
      product,
      amount
    }
  };
}

export function removeFromCartAction(
  product: Product
): RemoveFromCartAction {
  return {
    type: Actions.REMOVE_FROM_CART,
    payload: {
      product
    }
  };
}

export function checkoutCartAction(
  userName: string,
  items: Item[],
  paymentMethod = "MP"
): CheckoutCartAction {
  return {
    type: Actions.CHECKOUT_REQUEST,
    payload: {
      items,
      userName,
      paymentMethod
    }
  };
}

export function clearCartAction(): ClearCartAction {
  return {
    type: Actions.CLEAR
  };
}

export function checkoutSuccessAction(): CheckoutCartSuccessAction {
  return {
    type: Actions.CHECKOUT_SUCCESS
  };
}

export function checkoutFailAction(): CheckoutCartFailAction {
  return {
    type: Actions.CHECKOUT_FAIL
  };
}

export type AddToCartAction = {
  type: typeof Actions.ADD_TO_CART;
  payload: {
    product: Product;
  };
};

export type UpdateCartAction = {
  type: typeof Actions.UPDATE_CART;
  payload: {
    product: Product;
    amount: number;
  };
};

export type CheckoutCartAction = {
  type: typeof Actions.CHECKOUT_REQUEST;
  payload: {
    userName: string;
    paymentMethod: string;
    items: Item[];
  };
};

export type CheckoutCartFailAction = {
  type: typeof Actions.CHECKOUT_FAIL;
};

export type CheckoutCartSuccessAction = {
  type: typeof Actions.CHECKOUT_SUCCESS;
};

export type ClearCartAction = {
  type: typeof  Actions.CLEAR;
};

export type RemoveFromCartAction = {
  type: typeof Actions.REMOVE_FROM_CART;
  payload: {
    product: Product;
  };
};

export type CartAction =
  | AddToCartAction
  | RemoveFromCartAction
  | UpdateCartAction
  | CheckoutCartAction
  | CheckoutCartFailAction
  | CheckoutCartSuccessAction
  | ClearCartAction;
