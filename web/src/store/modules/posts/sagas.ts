import { put, call, all, takeEvery } from 'redux-saga/effects'

import { actionUpdatePosts, Actions, actionFailPosts } from './actions'
import { PostService } from '~/services/crud'

interface HandleFetchPostsAction {
  postService: PostService
  page: number
  type: string
}

export function* handleFetchPosts({
  page,
  postService,
}: HandleFetchPostsAction) {
  try {
    const response = yield call(() => postService.findAll(page))

    yield put(actionUpdatePosts(response.data))
  } catch (error) {
    yield put(actionFailPosts(error))
  }
}

export default all([takeEvery(Actions.FETCH_POSTS, handleFetchPosts)])
