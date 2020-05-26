import { put, call, all, takeEvery } from "redux-saga/effects";

import {
  fetchPostsFailAction,
  fetchPostsSuccessAction,
  Actions
} from "./actions";

import { posts as service, errors } from "~/services";

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
