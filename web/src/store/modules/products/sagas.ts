import { put, call, all, takeEvery } from "redux-saga/effects";

import { products as service, errors } from "~/services";

import {
  fetchProductsFailAction,
  fetchProductsSuccessAction,
  Actions
} from "./actions";

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
