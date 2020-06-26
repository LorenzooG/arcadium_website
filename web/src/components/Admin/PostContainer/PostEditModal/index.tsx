import React, { FormEvent, useContext, useState } from "react";

import ReactMde from "react-markdown-editor-lite";
import { toast } from "react-toastify";

import { Modal } from "~/components";

import { toastMessage } from "~/utils";

import { errors, locale, markdown, posts } from "~/services";
import { Post } from "~/services/entities";

import { Input, MarkdownWrapper } from "./styles";
import "react-markdown-editor-lite/lib/index.css";

import ContainerContext from "../PostContainerContext";

type Props = {
  open: boolean;
  setOpen: (value: boolean) => void;
  post?: Post;
  create?: boolean;
};

const AdminPostEditModal: React.FC<Props> = ({
                                               open,
                                               setOpen,
                                               post,
                                               create
                                             }) => {
  const [title, setTitle] = useState(post?.title);
  const [description, setDescription] = useState(post?.description);

  const context = useContext(ContainerContext);

  const localeEntity = locale.getTranslation("entity.post");
  const localeTitleMessage = locale.getTranslation("message.title");

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
        name: title,
        description
      };

      if (create) {
        const createdUser = await posts.store(content);

        context.addPost(createdUser);
      } else if (post) {
        const updatedUser = await posts
          .update(post.id, content)
          .then(() => posts.fetch(post.id));

        context.updatePost(post.id, updatedUser);
      }

      const localeSuccessNotification = locale.getTranslation(
        "notification.success"
      );

      toast.success(
        toastMessage(localeSuccessNotification.replace("$action", localeAction))
      );

      setTitle("");
      setDescription("");

      setOpen(false);
    } catch (exception) {
      errors.handleForException(exception);
    }
  }

  const localeTypeMessage = locale
    .getTranslation("message.type.the.thing")
    .replace("$thing", localeTitleMessage);

  const modalTitle = localeAction.toUpperCase();

  return (
    <Modal
      open={open}
      submit={modalTitle}
      handleSubmit={handleSubmit}
      setOpen={setOpen}
      title={modalTitle}
    >
      <form onSubmit={handleSubmit}>
        <Input
          type={"text"}
          placeholder={localeTypeMessage}
          value={title}
          onChange={event => setTitle(event.target.value)}
        />

        <MarkdownWrapper>
          <ReactMde
            value={description}
            onChange={event => setDescription(event.text)}
            renderHTML={text => markdown.render(text)}
          />
        </MarkdownWrapper>
      </form>
    </Modal>
  );
};

export default AdminPostEditModal;
