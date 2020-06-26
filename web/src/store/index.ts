import { applyMiddleware, createStore } from "redux";
import createSagaMiddleware from "redux-saga";

import { persistStore } from "redux-persist";

import { rootReducer, rootSaga } from "./modules";

const sagaMiddleware = createSagaMiddleware();

const store = createStore(rootReducer, applyMiddleware(sagaMiddleware));

sagaMiddleware.run(rootSaga);

export const persistor = persistStore(store);

export default store;
