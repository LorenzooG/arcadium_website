import { combineReducers } from 'redux'

import { all } from 'redux-saga/effects'

import postsReducer from './posts/reducer'
import postsSagas from './posts/sagas'

export const rootSaga = function* () {
  return yield all([postsSagas])
}

export const rootReducer = combineReducers({
  posts: postsReducer,
})
