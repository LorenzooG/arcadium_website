import React from "react";

import { Container, List } from "../styles";

import UserComponentLoading from "~/components/Admin/UserContainer/UserComponent/Loading";

const AdminUserListLoading: React.FC = () => {
  return (
    <Container>
      <List>
        {[1, 2, 3, 4, 5].map(key => (
          <UserComponentLoading key={key} />
        ))}
      </List>
    </Container>
  );
};

export default AdminUserListLoading;
