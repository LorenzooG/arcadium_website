import { put, call, all, takeEvery } from 'redux-saga/effects'

import { actionUpdatePosts, Actions, actionFailPosts } from './actions'

export function* handleFetchPosts() {
  try {
    const response = yield call(() =>
      fetch('http://localhost/posts').then(res => res.json())
    )

    yield put(actionUpdatePosts(response.data))
  } catch (error) {
    yield put(actionFailPosts(error))
  }
}

export default all([takeEvery(Actions.FETCH_POSTS, handleFetchPosts)])
