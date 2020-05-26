import React, { useContext, useRef } from "react";

import { toast } from "react-toastify";

import { toastMessage } from "~/utils";

import ReactMde from "react-markdown-editor-lite";
import { Modal } from "~/components";

import { products, markdown, errors, locale } from "~/services";
import { Product } from "~/services/entities";

import ContainerContext from "../ProductContainerContext";

import { Input, MarkdownWrapper } from "./styles";
import "react-markdown-editor-lite/lib/index.css";

type Props = {
  open: boolean;
  setOpen: (value: boolean) => void;
  product?: Product;
  create?: boolean;
};

const AdminProductEditModal: React.FC<Props> = ({
  open,
  setOpen,
  product,
  create
}) => {
  const formRef = useRef<HTMLFormElement>(null);

  const context = useContext(ContainerContext);

  const localeEntity = locale.getTranslation("entity.product");

  const localeAction = (create
    ? locale.getTranslation("action.create.entity")
    : locale.getTranslation("action.update.entity")
  ).replace("$entity", localeEntity);

  async function handleSubmit(event?: React.FormEvent) {
    event?.preventDefault();

    const localeTryNotification = locale.getTranslation("notification.try");

    toast.warn(
      toastMessage(localeTryNotification.replace("$action", localeAction))
    );

    if (!formRef.current) return;

    try {
      const content = new FormData(formRef.current);

      if (create) {
        const createdProduct = await products.store(content);

        context.addProduct(createdProduct);
      } else if (product) {
        const updatedProduct = await products
          .update(product.id, content)
          .then(() => products.fetch(product.id));

        context.updateProduct(product.id, updatedProduct);
      }

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

  const localeTitleMessage = locale.getTranslation("message.title");
  const localePriceMessage = locale.getTranslation("message.price");
  const localeCommandMessage = locale.getTranslation("message.command");

  const modalTitle = localeAction.toUpperCase();

  return (
    <Modal
      open={open}
      title={modalTitle}
      handleSubmit={handleSubmit}
      setOpen={setOpen}
      submit={modalTitle}
    >
      <form onSubmit={handleSubmit} ref={formRef}>
        <Input
          name={"name"}
          type={"text"}
          placeholder={localeTitleMessage}
          defaultValue={product?.name}
        />

        <Input
          name={"price"}
          type={"number"}
          placeholder={localePriceMessage}
          defaultValue={product?.price}
        />

        <Input name={"image"} type={"file"} />

        <Input
          name={"command"}
          type={"text"}
          placeholder={localeCommandMessage}
          defaultValue={product?.commands?.join(", ") ?? ""}
        />

        <MarkdownWrapper>
          <ReactMde
            name={"description"}
            renderHTML={text => markdown.render(text)}
          />
        </MarkdownWrapper>
      </form>
    </Modal>
  );
};

export default AdminProductEditModal;
