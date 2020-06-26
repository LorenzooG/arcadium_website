import React, { useState } from "react";

import { FiEdit, FiTrash } from "react-icons/fi";

import UserEditModal from "../UserEditModal";
import UserDeleteModal from "../UserDeleteModal";

import { User } from "~/services/entities";

import { Container, DeleteButton, EditButton } from "./styles";
import { requestPlayerHead } from "~/utils";

type Props = {
  user: User;
};

const AdminUserComponent: React.FC<Props> = ({ user }) => {
  const [editOpen, setEditOpen] = useState(false);
  const [deleteOpen, setDeleteOpen] = useState(false);

  return (
    <Container key={user.id}>
      <UserEditModal open={editOpen} setOpen={setEditOpen} user={user}/>
      <UserDeleteModal open={deleteOpen} setOpen={setDeleteOpen} id={user.id}/>

      <span>{user.id}</span>

      <img src={requestPlayerHead(user.userName)} alt={user.userName}/>

      <span>{user.name}</span>

      <div>
        <EditButton onClick={() => setEditOpen(true)}>
          <FiEdit/>
        </EditButton>

        <DeleteButton onClick={() => setDeleteOpen(true)}>
          <FiTrash/>
        </DeleteButton>
      </div>
    </Container>
  );
};

export default AdminUserComponent;
