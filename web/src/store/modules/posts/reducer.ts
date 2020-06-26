/* eslint-disable indent */
import { AnyAction } from 'redux'

import produce from 'immer'

import { Post } from '~/services/entities'

import { Actions } from './actions'
import { HYDRATE } from 'next-redux-wrapper'

interface State {
  posts: Post[]
  loading: boolean
}

const INITIAL_STATE: State = {
  posts: [],
  loading: true,
}

const reducer = (state: State = INITIAL_STATE, action: AnyAction) => {
  return produce(state, draft => {
    switch (action.type) {
      case HYDRATE:
        draft.posts = action.payload.posts
        draft.loading = action.payload.loading

        break

      case Actions.FETCH_POSTS:
        draft.posts = []
        draft.loading = true

        break
      case Actions.UPDATE_POSTS:
        draft.posts = action.payload
        draft.loading = false

        break
    }
  })
}

export default reducer
