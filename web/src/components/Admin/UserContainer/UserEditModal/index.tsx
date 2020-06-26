import React, { FormEvent, useContext, useState } from "react";

import { requestPlayerHead, toastMessage } from "~/utils";

import { toast } from "react-toastify";

import { useDispatch, useSelector } from "react-redux";
import { editAccountSuccessAction } from "~/store/modules/auth/actions";

import { Modal } from "~/components";

import { errors, locale, users as service } from "~/services";
import { User } from "~/services/entities";

import { RootState } from "~/store/modules";

import { Avatar, Input } from "./styles";

import ContainerContext from "../UserContainerContext";

type Props = {
  open: boolean;
  setOpen: (value: boolean) => void;
  user?: User;
  create?: boolean;
};

const AdminUserEditModal: React.FC<Props> = ({
                                               open,
                                               setOpen,
                                               user,
                                               create
                                             }) => {
  const currentUser = useSelector<RootState, User | null>(
    state => state.auth.account
  );

  const [userName, setUserName] = useState(user?.userName);
  const [name, setName] = useState(user?.name);
  const [email, setEmail] = useState(user?.email);
  const [password, setPassword] = useState("");

  const context = useContext(ContainerContext);

  const dispatch = useDispatch();

  const localeEntity = locale.getTranslation("entity.user");

  const localeAction = (create
      ? locale.getTranslation("action.create.entity")
      : locale.getTranslation("action.update.entity")
  ).replace("$entity", localeEntity);

  async function handleSubmit(event?: FormEvent) {
    event?.preventDefault();

    const localeTryNotification = locale.getTranslation("notification.try");

    toast.warn(
      toastMessage(localeTryNotification.replace("$action", localeAction))
    );

    try {
      const content = {
        // eslint-disable-next-line @typescript-eslint/camelcase
        user_name: userName,
        name,
        password: password?.length === 0 ? undefined : password,
        email
      };

      if (create) {
        const createdUser = await service.store(content);

        context.addUser(createdUser);
      } else if (user && currentUser) {
        const updatedUser = await service
          .update(user.id, content)
          .then(() => service.fetch(user.id));

        context.updateUser(user.id, updatedUser);

        if (user.id === currentUser.id) {
          dispatch(editAccountSuccessAction(await service.user()));
        }
      }

      const localeSuccessNotification = locale.getTranslation(
        "notification.success"
      );

      toast.success(
        toastMessage(localeSuccessNotification.replace("$action", localeAction))
      );

      setEmail("");
      setUserName("");
      setName("");
      setPassword("");

      setOpen(false);
    } catch (exception) {
      errors.handleForException(exception);
    }
  }

  const localeEmailMessage = locale.getTranslation("message.email");
  const localeNameMessage = locale.getTranslation("message.name");
  const localePasswordMessage = locale.getTranslation("message.password");
  const localeUserNameMessage = locale.getTranslation("message.user_name");

  const modalTitle = localeAction.toUpperCase();

  return (
    <Modal
      open={open}
      submit={modalTitle}
      handleSubmit={handleSubmit}
      setOpen={setOpen}
      title={modalTitle}
    >
      <Avatar src={requestPlayerHead(userName ?? "undefined")} alt={userName}/>

      <form onSubmit={handleSubmit}>
        <Input
          type={"text"}
          placeholder={localeNameMessage}
          value={name}
          onChange={event => setName(event.target.value)}
        />

        <Input
          type={"email"}
          placeholder={localeEmailMessage}
          value={email}
          onChange={event => setEmail(event.target.value)}
        />

        <Input
          type={"text"}
          placeholder={localeUserNameMessage}
          value={userName}
          onChange={event => setUserName(event.target.value)}
        />

        <Input
          type={"password"}
          placeholder={localePasswordMessage}
          value={password}
          onChange={event => setPassword(event.target.value)}
        />
      </form>
    </Modal>
  );
};

export default AdminUserEditModal;
