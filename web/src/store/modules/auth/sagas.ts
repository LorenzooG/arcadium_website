import { all, call, put, takeEvery } from "redux-saga/effects";

import { toast } from "react-toastify";

import { toastMessage } from "~/utils";

import { auth as service, errors, locale, users } from "~/services";

import {
  Actions,
  editAccountSuccessAction,
  loginFailAction,
  LoginRequestAction,
  loginSuccessAction,
  UpdateAccountRequestAction
} from "./actions";

export function* handleLogin({ payload }: LoginRequestAction) {
  const localeAction = locale.getTranslation("action.login");

  const localeTryNotification = locale.getTranslation("notification.try");

  toast.warn(
    toastMessage(localeTryNotification.replace("$action", localeAction))
  );

  try {
    const response = yield call(service.login, payload.email, payload.password);

    const user = yield call(users.user, response.token);

    yield put(loginSuccessAction(user, response.token));

    const localeSuccessNotification = locale.getTranslation(
      "notification.success"
    );

    toast.success(
      toastMessage(localeSuccessNotification.replace("$action", localeAction))
    );
  } catch (exception) {
    yield put(loginFailAction());

    errors.handleForException(exception);
  }
}

export function* handleUpdateAccount({
                                       payload: { id, user: userConstructor }
                                     }: UpdateAccountRequestAction) {
  const localeEntity = locale.getTranslation("entity.user");

  const localeAction = locale
    .getTranslation("action.update.entity")
    .replace("$entity", localeEntity);

  const localeTryNotification = locale.getTranslation("notification.try");

  toast.warn(
    toastMessage(localeTryNotification.replace("$action", localeAction))
  );

  try {
    yield call(users.update, id, userConstructor);

    const user = yield call(users.user);

    yield put(editAccountSuccessAction(user));

    const localeSuccessNotification = locale.getTranslation(
      "notification.success"
    );

    toast.success(
      toastMessage(localeSuccessNotification.replace("$action", localeAction))
    );
  } catch (exception) {
    errors.handleForException(exception);
  }
}

export default all([
  takeEvery(Actions.LOGIN_REQUEST, handleLogin),
  takeEvery(Actions.UPDATE_ACCOUNT_REQUEST, handleUpdateAccount)
]);
