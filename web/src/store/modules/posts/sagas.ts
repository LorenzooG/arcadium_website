import { put, call, all, takeEvery } from 'redux-saga/effects'

import { actionUpdatePosts, Actions, actionFailPosts } from './actions'
import { PostService, getService } from '~/services/crud'

export function* handleFetchPosts() {
  const postService = getService(PostService)

  try {
    const response = yield call(() => postService.findAll())

    yield put(actionUpdatePosts(response.data))
  } catch (error) {
    yield put(actionFailPosts(error))
  }
}

export default all([takeEvery(Actions.FETCH_POSTS, handleFetchPosts)])
