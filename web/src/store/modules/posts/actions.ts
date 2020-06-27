import { AnyAction } from 'redux'

import { Post } from '~/services/entities'

export class Actions {
  public static readonly FETCH_POSTS = '@posts/FETCH'
  public static readonly UPDATE_POSTS = '@posts/UPDATE'
  public static readonly FAIL_POSTS = '@posts/FAIL'
}

export const actionFetchPosts = (page = 1): AnyAction => ({
  type: Actions.FETCH_POSTS,
  page,
})

export const actionUpdatePosts = (posts: Post[]): AnyAction => ({
  type: Actions.UPDATE_POSTS,
  payload: posts,
})

export const actionFailPosts = (error: Error): AnyAction => ({
  type: Actions.FAIL_POSTS,
  payload: error,
})
