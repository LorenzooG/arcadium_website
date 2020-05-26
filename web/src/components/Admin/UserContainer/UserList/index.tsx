import React, { useState } from "react";

import { FiPlusCircle } from "react-icons/all";

import UserComponent from "../UserComponent";
import UserEditModal from "../UserEditModal";

import { User } from "~/services/entities";

import { List, Container, AddUserButton } from "./styles";

type Props = {
  users: User[];
};

const AdminUserList: React.FC<Props> = ({ users }) => {
  const [open, setOpen] = useState(false);

  return (
    <Container>
      <UserEditModal create open={open} setOpen={setOpen} />

      <List>
        {users.map(user => (
          <UserComponent user={user} key={user.id} />
        ))}

        <li>
          <AddUserButton onClick={() => setOpen(true)}>
            <FiPlusCircle />
          </AddUserButton>
        </li>
      </List>
    </Container>
  );
};

export default AdminUserList;
