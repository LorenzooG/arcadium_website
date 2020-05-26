/* tslint:disable:ter-indent */
/* eslint-disable indent */
import produce from "immer";

import { toast } from "react-toastify";

import { toastMessage } from "~/utils";

import { locale } from "~/services";
import { Product } from "~/services/entities";

import { Actions, CartAction } from "./actions";

export type Item = {
  product: Product;
  amount: number;
};

export type CartState = {
  loading: boolean;
  error: boolean;
  blocked: boolean;
  items: Item[];
};

const INITIAL_STATE: CartState = {
  loading: false,
  error: false,
  blocked: false,
  items: []
};

function sendToast() {
  const localeAction = locale.getTranslation("action.add.to.cart");

  const localeSuccessNotification = locale.getTranslation(
    "notification.success"
  );

  toast.success(
    toastMessage(localeSuccessNotification.replace("$action", localeAction))
  );
}

function sendLimitToast() {
  const localeAction = locale.getTranslation("error.cart.limit");

  toast.error(toastMessage(localeAction));
}

export default (state = INITIAL_STATE, action: CartAction) => {
  return produce(state, draft => {
    switch (action.type) {
      case Actions.ADD_TO_CART:
        (() => {
          const predicate = (item: Item) => {
            return item.product.id === action.payload.product.id;
          };

          if (draft.items.find(predicate)) {
            const index = draft.items.findIndex(predicate);
            const amount = draft.items[index].amount;

            if (amount >= 40) return sendLimitToast();

            draft.items[index].amount = amount + 1;
          } else {
            draft.items.push({
              product: action.payload.product,
              amount: 1
            });
          }

          sendToast();
        })();
        break;
      case Actions.UPDATE_CART:
        (() => {
          const index = draft.items.findIndex(
            item => item.product.id === action.payload.product.id
          );

          draft.items[index].amount =
            action.payload.amount >= 40 ? 40 : action.payload.amount;
        })();
        break;
      case Actions.REMOVE_FROM_CART:
        (() => {
          const index = draft.items.findIndex(
            item => item.product.id === action.payload.product.id
          );

          draft.items.splice(index, 1);
        })();
        break;
      case Actions.CLEAR:
        draft.items = [];

        break;
      case Actions.CHECKOUT_REQUEST:
        draft.loading = false;
        draft.error = false;
        draft.blocked = true;

        break;
      case Actions.CHECKOUT_FAIL:
        draft.loading = false;
        draft.error = true;

        break;
      case Actions.CHECKOUT_SUCCESS:
        draft.loading = false;
        draft.items = [];
        draft.blocked = false;

        break;
    }
  });
};
