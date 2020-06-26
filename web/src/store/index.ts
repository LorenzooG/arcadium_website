import { createStore } from 'redux'
import { MakeStore, createWrapper } from 'next-redux-wrapper'

import { rootReducer } from './modules'

export const makeStore: MakeStore = () => createStore(rootReducer)

export const wrapper = createWrapper(makeStore, {
  debug: true,
})
