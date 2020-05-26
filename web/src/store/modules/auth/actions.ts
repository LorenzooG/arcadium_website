import { User } from "~/services/entities";

export enum Actions {
  LOGIN_REQUEST = "@auth/LOGIN_REQUEST",
  LOGOUT = "@auth/LOGOUT",
  LOGIN_SUCCESS = "@auth/LOGIN_SUCCESS",
  LOGIN_FAIL = "@auth/LOGIN_FAIL",
  UPDATE_ACCOUNT_SUCCESS = "@auth/ACCOUNT_UPDATE_SUCCESS",
  UPDATE_ACCOUNT_REQUEST = "@auth/ACCOUNT_UPDATE_REQUEST"
}

export function loginFailAction(): LoginFailAction {
  return {
    type: Actions.LOGIN_FAIL
  };
}

export function loginRequestAction(
  email: string,
  password: string
): LoginRequestAction {
  return {
    type: Actions.LOGIN_REQUEST,
    payload: {
      email,
      password
    }
  };
}

export function logoutAction(): LoginLogoutAction {
  return {
    type: Actions.LOGOUT
  };
}

export function loginSuccessAction(user: User, token: string): LoginSuccessAction {
  return {
    type: Actions.LOGIN_SUCCESS,
    payload: {
      user,
      token
    }
  };
}

export function editAccountRequestAction(
  id: number,
  user: object
): UpdateAccountRequestAction {
  return {
    type: Actions.UPDATE_ACCOUNT_REQUEST,
    payload: {
      id,
      user
    }
  };
}

export function editAccountSuccessAction(
  user: User
): UpdateAccountSuccessAction {
  return {
    type: Actions.UPDATE_ACCOUNT_SUCCESS,
    payload: {
      user
    }
  };
}

export type LoginRequestAction = {
  type: typeof Actions.LOGIN_REQUEST;
  payload: {
    email: string;
    password: string;
  };
};

export type LoginSuccessAction = {
  type: typeof Actions.LOGIN_SUCCESS;
  payload: {
    user: User;
    token: string;
  };
};

export type UpdateAccountRequestAction = {
  type: typeof Actions.UPDATE_ACCOUNT_REQUEST;
  payload: {
    id: number;
    user: object;
  };
};

export type UpdateAccountSuccessAction = {
  type: typeof Actions.UPDATE_ACCOUNT_SUCCESS;
  payload: {
    user: User;
  };
};

export type LoginFailAction = {
  type: typeof Actions.LOGIN_FAIL;
};

export type LoginLogoutAction = {
  type: typeof Actions.LOGOUT;
};

export type LoginAction =
  | LoginFailAction
  | LoginRequestAction
  | LoginSuccessAction
  | LoginLogoutAction
  | UpdateAccountRequestAction
  | UpdateAccountSuccessAction;
