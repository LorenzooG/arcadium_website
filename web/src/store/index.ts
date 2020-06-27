import { createStore, applyMiddleware, Store } from 'redux'
import createSagaMiddleware, { Task } from 'redux-saga'
import { persistStore, Persistor } from 'redux-persist'
import { MakeStore, createWrapper } from 'next-redux-wrapper'

import { rootReducer, rootSaga } from './modules'

export interface SagaStore extends Store {
  sagaTask?: Task
  __persistor: Persistor
}

export const makeStore: MakeStore = (context: any) => {
  const sagaMiddleware = createSagaMiddleware()

  if (context.isServer) {
    return createStore(rootReducer, applyMiddleware(sagaMiddleware))
  }

  const store = createStore(
    rootReducer,
    applyMiddleware(sagaMiddleware)
  ) as SagaStore

  store.sagaTask = sagaMiddleware.run(rootSaga)
  store.__persistor = persistStore(store)

  return store
}

export const wrapper = createWrapper(makeStore, {
  debug: true,
})
