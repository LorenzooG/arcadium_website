import { all, call, put, takeEvery } from "redux-saga/effects";

import { toast } from "react-toastify";

import { toastMessage } from "~/utils";

import { errors, locale, payments as service } from "~/services";

import { Actions, CheckoutCartAction, checkoutFailAction, checkoutSuccessAction } from "./actions";

export function* handleCheckout({
                                  payload: { items, paymentMethod, userName }
                                }: CheckoutCartAction) {
  const localeAction = locale.getTranslation("action.checkout.cart");

  const localeTryNotification = locale.getTranslation("notification.try");

  toast.warn(
    toastMessage(localeTryNotification.replace("$action", localeAction))
  );

  try {
    const response = yield call(
      service.checkout,
      userName,
      items.map(({ product, amount }) => ({
        product: product.id,
        amount
      })),
      paymentMethod
    );

    const localeSuccessNotification = locale.getTranslation(
      "notification.success"
    );

    toast.success(
      toastMessage(localeSuccessNotification.replace("$action", localeAction))
    );

    yield put(checkoutSuccessAction());

    window.location.assign(response.link);
  } catch (exception) {
    yield put(checkoutFailAction());

    errors.handleForException(exception);
  }
}

export default all([takeEvery(Actions.CHECKOUT_REQUEST, handleCheckout)]);
