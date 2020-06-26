import { all, call, put, takeEvery } from "redux-saga/effects";

import { Actions, fetchPostsFailAction, fetchPostsSuccessAction } from "./actions";

import { errors, posts as service } from "~/services";

export function* handleFetchPosts() {
  try {
    const response = yield call(service.fetchAll);

    yield put(fetchPostsSuccessAction(response));
  } catch (exception) {
    yield put(fetchPostsFailAction());

    errors.handleForException(exception);
  }
}

export default all([takeEvery(Actions.FETCH_REQUEST, handleFetchPosts)]);
