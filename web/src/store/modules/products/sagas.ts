import { all, call, put, takeEvery } from "redux-saga/effects";

import { errors, products as service } from "~/services";

import { Actions, fetchProductsFailAction, fetchProductsSuccessAction } from "./actions";

export function* handleFetchProducts() {
  try {
    const response = yield call(service.fetchAll);

    yield put(fetchProductsSuccessAction(response));
  } catch (exception) {
    yield put(fetchProductsFailAction());

    errors.handleForException(exception);
  }
}

export default all([takeEvery(Actions.FETCH_REQUEST, handleFetchProducts)]);
