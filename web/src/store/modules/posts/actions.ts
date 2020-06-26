import { Post } from '~/services/entities'

export enum Actions {
  FETCH_REQUEST = '@posts/FETCH_REQUEST',
  FETCH_SUCCESS = '@posts/FETCH_SUCCESS',
  FETCH_FAIL = '@posts/FETCH_FAIL'
}

export function fetchPostsRequestAction(): FetchPostsRequestAction {
  return {
    type: Actions.FETCH_REQUEST
  }
}

export function fetchPostsFailAction(): FetchPostsFailAction {
  return {
    type: Actions.FETCH_FAIL
  }
}

export function fetchPostsSuccessAction(
  posts: Post[]
): FetchPostsSuccessAction {
  return {
    type: Actions.FETCH_SUCCESS,
    payload: posts
  }
}

export type FetchPostsSuccessAction = {
  type: typeof Actions.FETCH_SUCCESS
  payload: Post[]
}

export type FetchPostsFailAction = {
  type: typeof Actions.FETCH_FAIL
}

export type FetchPostsRequestAction = {
  type: typeof Actions.FETCH_REQUEST
}

export type PostsAction =
  | FetchPostsSuccessAction
  | FetchPostsFailAction
  | FetchPostsRequestAction
