/* tslint:disable:ter-indent */
/* eslint-disable indent */
import { User } from "~/services/entities";

import produce from "immer";

import { Actions, LoginAction } from "./actions";

export type AuthState = {
  loading: boolean;
  error: boolean;
  account: User | null;
  isLogged: boolean;
  isAdmin: boolean;
  token: string;
};

const INITIAL_STATE: AuthState = {
  loading: false,
  error: false,
  isAdmin: false,
  isLogged: false,
  token: "",
  account: null
};

export default (state = INITIAL_STATE, action: LoginAction) => {
  return produce(state, draft => {
    switch (action.type) {
      case Actions.LOGIN_REQUEST:
        draft.loading = true;

        break;
      case Actions.LOGIN_FAIL:
        draft.loading = false;
        draft.error = true;
        draft.isAdmin = false;
        draft.isLogged = false;

        break;
      case Actions.LOGIN_SUCCESS:
        draft.loading = false;
        draft.error = false;
        draft.account = action.payload.user;
        draft.token = action.payload.token;
        draft.isAdmin = action.payload.user.isAdmin;
        draft.isLogged = true;

        break;
      case Actions.UPDATE_ACCOUNT_SUCCESS:
        draft.loading = false;
        draft.error = false;
        draft.account = action.payload.user;
        draft.isAdmin = action.payload.user.isAdmin;
        draft.isLogged = true;

        break;
      case Actions.UPDATE_ACCOUNT_REQUEST:
        draft.loading = true;

        break;
      case Actions.LOGOUT:
        draft.isLogged = false;
        draft.isAdmin = false;
        draft.token = "";
        draft.account = null;

        break;
    }
  });
};
