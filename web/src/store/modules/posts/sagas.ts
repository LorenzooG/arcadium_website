import { put, call, all, takeEvery } from 'redux-saga/effects'

import { AnyAction } from 'redux'

import { actionUpdatePosts, Actions, actionFailPosts } from './actions'
import { PostService, getService } from '~/services/crud'

export function* handleFetchPosts({ page }: AnyAction) {
  const postService = getService(PostService)

  try {
    const response = yield call(() => postService.findAll(page))

    yield put(actionUpdatePosts(response.data))
  } catch (error) {
    yield put(actionFailPosts(error))
  }
}

export default all([takeEvery(Actions.FETCH_POSTS, handleFetchPosts)])
