import { Product } from "~/services/entities";

export enum Actions {
  FETCH_REQUEST = "@products/FETCH_REQUEST",
  FETCH_SUCCESS = "@products/FETCH_SUCCESS",
  FETCH_FAIL = "@products/FETCH_FAIL"
}

export function fetchProductsRequestAction(): FetchProductsRequestAction {
  return {
    type: Actions.FETCH_REQUEST
  };
}

export function fetchProductsFailAction(): FetchProductsFailAction {
  return {
    type: Actions.FETCH_FAIL
  };
}

export function fetchProductsSuccessAction(
  posts: Product[]
): FetchProductsSuccessAction {
  return {
    type: Actions.FETCH_SUCCESS,
    payload: posts
  };
}

export type FetchProductsSuccessAction = {
  type: typeof Actions.FETCH_SUCCESS;
  payload: Product[];
};

export type FetchProductsFailAction = {
  type: typeof Actions.FETCH_FAIL;
};

export type FetchProductsRequestAction = {
  type: typeof Actions.FETCH_REQUEST;
};

export type ProductsAction =
  | FetchProductsSuccessAction
  | FetchProductsFailAction
  | FetchProductsRequestAction;
