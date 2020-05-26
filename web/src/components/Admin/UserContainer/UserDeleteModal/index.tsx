import React, { useContext } from "react";

import { toast } from "react-toastify";

import { toastMessage } from "~/utils";

import { useDispatch, useSelector } from "react-redux";

import { DeleteEntityModal } from "~/components/Admin";

import { logoutAction } from "~/store/modules/auth/actions";
import { RootState } from "~/store/modules";

import { errors, locale, users } from "~/services";
import { User } from "~/services/entities";

import ContainerContext from "../UserContainerContext";

type Props = {
  open: boolean;
  setOpen: (value: boolean) => void;
  id: number;
};

const UserDeleteModal: React.FC<Props> = ({ open, setOpen, id }) => {
  const currentUser = useSelector<RootState, User | null>(
    state => state.auth.account
  );

  const context = useContext(ContainerContext);

  const dispatch = useDispatch();

  async function handleSubmit() {
    const localeEntity = locale.getTranslation("entity.user");

    const localeAction = locale
      .getTranslation("action.delete.entity")
      .replace("$entity", localeEntity);

    const localeTryNotification = locale.getTranslation("notification.try");

    toast.warn(
      toastMessage(localeTryNotification.replace("$action", localeAction))
    );

    if (currentUser) {
      try {
        await users.delete(id);

        context.deleteUser(id);

        if (id === currentUser.id) {
          dispatch(logoutAction());
        }

        const localeSuccessNotification = locale.getTranslation(
          "notification.success"
        );

        toast.success(
          toastMessage(
            localeSuccessNotification.replace("$action", localeAction)
          )
        );

        setOpen(false);
      } catch (exception) {
        errors.handleForException(exception);
      }
    }
  }

  return (
    <DeleteEntityModal
      handleSubmit={handleSubmit}
      open={open}
      setOpen={setOpen}
      entity={locale.getTranslation("entity.user")}
      id={id}
    />
  );
};

export default UserDeleteModal;
