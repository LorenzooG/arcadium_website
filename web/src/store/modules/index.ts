import { combineReducers } from "redux";
import { all } from "redux-saga/effects";

import authReducer, { AuthState } from "./auth/reducer";
import cartReducer, { CartState } from "./cart/reducer";
import productsReducer, { ProductsState } from "./products/reducer";
import postsReducer, { PostsState } from "~/store/modules/posts/reducer";

import authSaga from "./auth/sagas";
import productsSaga from "./products/sagas";
import postsSaga from "./posts/sagas";
import cartSage from "./cart/sagas";

import { persistReducer } from "redux-persist";
import storage from "redux-persist/lib/storage";

const cartConfig = {
  key: "cart",
  storage
};

const authConfig = {
  key: "auth",
  storage
};

export function* rootSaga() {
  return yield all([authSaga, productsSaga, postsSaga, cartSage]);
}

export interface RootState {
  auth: AuthState;
  cart: CartState;
  posts: PostsState;
  products: ProductsState;
}

export const rootReducer = combineReducers({
  auth: persistReducer(authConfig, authReducer),
  cart: persistReducer(cartConfig, cartReducer),
  posts: postsReducer,
  products: productsReducer
});
