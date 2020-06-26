import { put, call, all, takeEvery } from 'redux-saga/effects'

import { actionUpdatePosts, Actions } from './actions'

export function* handleFetchPosts() {
  const response = yield call(() =>
    fetch('http://localhost/posts').then(res => res.json())
  )

  yield put(actionUpdatePosts(response.data))
}

export default all([takeEvery(Actions.FETCH_POSTS, handleFetchPosts)])
