/* tslint:disable:ter-indent */
/* eslint-disable indent */
import { Product } from "~/services/entities";

import produce from "immer";

import { ProductsAction, Actions } from "./actions";

export type ProductsState = {
  loading: boolean;
  error: boolean;
  products: Product[];
};

const INITIAL_STATE: ProductsState = {
  loading: true,
  error: false,
  products: []
};

export default function reducer(state = INITIAL_STATE, action: ProductsAction) {
  return produce(state, draft => {
    switch (action.type) {
      case Actions.FETCH_SUCCESS:
        draft.loading = false;
        draft.error = false;
        draft.products = action.payload;

        break;
      case Actions.FETCH_FAIL:
        draft.loading = false;
        draft.error = true;

        break;
      case Actions.FETCH_REQUEST:
        draft.loading = true;
        draft.error = false;

        break;
    }
  });
}
