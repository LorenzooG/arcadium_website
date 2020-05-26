import React, { useState } from "react";

import { FiEdit, FiTrash, FiFileText } from "react-icons/fi";

import PostEditModal from "~/components/Admin/PostContainer/PostEditModal";
import PostDeleteModal from "~/components/Admin/PostContainer/PostDeleteModal";

import { Post } from "~/services/entities";

import { DeleteButton, EditButton, Container, Icon } from "./styles";

type Props = {
  post: Post;
};

const AdminPostComponent: React.FC<Props> = ({ post }) => {
  const [editOpen, setEditOpen] = useState(false);
  const [deleteOpen, setDeleteOpen] = useState(false);

  return (
    <Container key={post.id}>
      <PostEditModal open={editOpen} setOpen={setEditOpen} post={post} />
      <PostDeleteModal open={deleteOpen} setOpen={setDeleteOpen} id={post.id} />

      <span>{post.id}</span>

      <Icon>
        <FiFileText />
      </Icon>

      <span>{post.title}</span>

      <div>
        <EditButton onClick={() => setEditOpen(true)}>
          <FiEdit />
        </EditButton>

        <DeleteButton onClick={() => setDeleteOpen(true)}>
          <FiTrash />
        </DeleteButton>
      </div>
    </Container>
  );
};

export default AdminPostComponent;
