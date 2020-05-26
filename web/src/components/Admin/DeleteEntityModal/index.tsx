import React from "react";

import { Modal } from "~/components";

import { locale } from "~/services";

type Props = {
  open: boolean;
  setOpen: (value: boolean) => void;
  handleSubmit: () => void;
  entity: string;
  id: number;
};

const DeleteEntityModal: React.FC<Props> = ({
  setOpen,
  handleSubmit,
  open,
  id,
  entity
}) => {
  return (
    <Modal
      handleSubmit={handleSubmit}
      open={open}
      setOpen={setOpen}
      title={locale
        .getTranslation("action.delete.entity")
        .replace("$entity", entity)}
      submit={locale.getTranslation("action.confirm.delete")}
    >
      {locale
        .getTranslation("action.delete.entity.text")
        .replace("$entity", entity)
        .replace("$id", id.toString())}
    </Modal>
  );
};

export default DeleteEntityModal;
