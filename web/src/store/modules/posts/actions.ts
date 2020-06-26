import { AnyAction } from 'redux'

import { Post } from '~/services/entities'

export class Actions {
  public static readonly FETCH_POSTS = '@posts/FETCH'
  public static readonly UPDATE_POSTS = '@posts/UPDATE'
}

export const actionFetchPosts = (): AnyAction => ({
  type: Actions.FETCH_POSTS,
})

export const actionUpdatePosts = (posts: Post[]): AnyAction => ({
  type: Actions.UPDATE_POSTS,
  payload: posts,
})
