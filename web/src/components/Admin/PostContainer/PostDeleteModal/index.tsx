import React, { useContext } from "react";

import { toast } from "react-toastify";

import { toastMessage } from "~/utils";

import { DeleteEntityModal } from "~/components/Admin";

import { errors, locale, posts as service } from "~/services";

import ContainerContext from "../PostContainerContext";

type Props = {
  open: boolean;
  setOpen: (value: boolean) => void;
  id: number;
};

const PostDeleteModal: React.FC<Props> = ({ open, setOpen, id }) => {
  const context = useContext(ContainerContext);

  async function handleSubmit() {
    const localeEntity = locale.getTranslation("entity.post");

    const localeAction = locale
      .getTranslation("action.delete.entity")
      .replace("$entity", localeEntity);

    const localeTryNotification = locale.getTranslation("notification.try");

    toast.warn(
      toastMessage(localeTryNotification.replace("$action", localeAction))
    );

    try {
      await service.delete(id);

      context.deletePost(id);

      const localeSuccessNotification = locale.getTranslation(
        "notification.success"
      );

      toast.success(
        toastMessage(localeSuccessNotification.replace("$action", localeAction))
      );

      setOpen(false);
    } catch (exception) {
      errors.handleForException(exception);
    }
  }

  return (
    <DeleteEntityModal
      handleSubmit={handleSubmit}
      open={open}
      setOpen={setOpen}
      entity={locale.getTranslation("entity.post")}
      id={id}
    />
  );
};

export default PostDeleteModal;
