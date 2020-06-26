import React from "react";

import { Container, List } from "../styles";

import PostComponentLoading from "~/components/Admin/PostContainer/PostComponent/Loading";

const AdminPostListLoading: React.FC = () => {
  return (
    <Container>
      <List>
        {[1, 2, 3, 4, 5].map(key => (
          <PostComponentLoading key={key}/>
        ))}
      </List>
    </Container>
  );
};

export default AdminPostListLoading;
